// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"context"
	"encoding/json"
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis"

	"github.com/sirupsen/logrus"
	"helm.sh/helm/v3/pkg/releaseutil"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"io/ioutil"
	"os"
	"strconv"

	"gopkg.in/yaml.v3"
	"helm.sh/helm/v3/pkg/cli/values"
	"helm.sh/helm/v3/pkg/release"
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/types"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
	namespace   string

	logger logrus.FieldLogger
}

func NewApps(ctx context.Context, clusterName, namespace string) *Manager {
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName, namespace: namespace,
		ks:     cluster.Get(clusterName),
		logger: logging.DefaultLogger().WithContext(ctx),
	}
}

func (m *Manager) Install(name string, body model.AppCreateOrUpdateModel) error {
	logger := m.logger.WithFields(logrus.Fields{
		"name": name, "namespace": body.Namespace,
	})
	h, err := helm.NamespaceScope(m.namespace)
	if err != nil {
		return err
	}

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}
	options := &values.Options{Values: settings}
	logger.Infof("user custom settings is %+v", settings)

	if len(body.SettingsMap) > 0 {
		logger.Infof("user custom settingsMap is %+v", body.SettingsMap)
		f, err := writeValuesFile(body.SettingsMap)
		if err != nil {
			logger.WithError(err).Error("write values file failed")
		}
		defer os.Remove(f)
		options.ValueFiles = []string{f}
	}

	if err = helm.RepoUpdate(); err != nil {
		logger.WithError(err).Error("helm update repo failed")
		return err
	}

	rel, err := h.Install(name, genChart(body.Channel, body.Chart), body.Version, options)
	if err != nil {
		logger.WithError(err).Error("helm install failed")
		analysis.Install(body.Chart, body.Version).AddFeature().Fail(err)
		if _, e := h.GetRelease(name); e == nil {
			logger.Info("recycle incomplete release")
			_ = h.Uninstall(name)
		}
		return err
	}
	secretMeta := metav1.ObjectMeta{
		Labels: map[string]string{
			constant.LabelApplication: "true",
		},
		Annotations: map[string]string{
			constant.AnnotationAppChannel: body.Channel,
		},
	}
	if body.Username != "" {
		secretMeta.Annotations[constant.AnnotationAppCreator] = body.Username
	}
	err = completeAppLabels(m.ctx, rel, m.ks, logger, secretMeta)
	analysis.Install(body.Chart, body.Version).AddFeature("gdb", "ldap").Success()
	return err
}

func (m *Manager) UnInstall(name string) error {
	h, err := helm.NamespaceScope(m.namespace)
	if err != nil {
		return err
	}

	err = h.Uninstall(name)
	return err
}

func (m *Manager) GetApp(name string) (*Instance, error) {
	app := newApp(m.ctx, m, name)
	if app.release == nil {
		return nil, ErrAppNotFound
	}

	err := app.prepare()
	return app, err
}

func (m *Manager) Upgrade() error {
	// todo release on 1.3 and remove in 1.4
	// set all exists helm secret as quickon-apps
	// this label will add to every new-installed app
	l, err := m.ks.Clients.Base.CoreV1().Secrets("default").List(m.ctx, metav1.ListOptions{FieldSelector: "type=helm.sh/release.v1"})
	if err != nil {
		return err
	}

	if len(l.Items) > 0 {
		m.logger.Infof("%d secret of helm release will be check and upgrade", len(l.Items))
	}
	for _, s := range l.Items {
		if v, ok := s.Labels[constant.LabelApplication]; ok && v == "true" {
			continue
		}
		patchContent := fmt.Sprintf(`{"metadata":{"labels":{"%s":"true"}}}`, constant.LabelApplication)
		m.logger.Infof("patch app label for secret %s", s.Name)
		_, err = m.ks.Clients.Base.CoreV1().Secrets(s.Namespace).Patch(m.ctx, s.Name, types.MergePatchType, []byte(patchContent), metav1.PatchOptions{})
		if err != nil {
			m.logger.WithError(err).Error("patch app label failed, secret is %s", s.Name)
		}
	}
	return nil
}

func (m *Manager) ListAllApplications() (interface{}, error) {
	lbs := labels.Set{"owner": "helm", constant.LabelApplication: "true"}.AsSelector()
	secrets, err := m.ks.Store.ListSecrets("", lbs)
	if err != nil {
		return nil, err
	}

	groupedRevisions := make(map[string][]*release.Release)
	for _, secret := range secrets {
		rls, err := decodeRelease(string(secret.Data["release"]))
		if err != nil {
			continue
		}
		namespacedName := fmt.Sprintf("%s/%s", secret.Namespace, secret.Labels["name"])
		if _, ok := groupedRevisions[namespacedName]; !ok {
			groupedRevisions[namespacedName] = make([]*release.Release, 0)
		}

		groupedRevisions[namespacedName] = append(groupedRevisions[namespacedName], rls)
	}

	var result []*model.AppRespAppDetail

	for _, revisions := range groupedRevisions {
		releaseutil.Reverse(revisions, releaseutil.SortByRevision)
		last := revisions[0]

		secret, err := loadAppSecret(m.ctx, last.Name, last.Namespace, last.Version, m.ks)
		if err != nil {
			continue
		}

		info := &model.AppRespAppDetail{
			Name:      last.Name,
			Namespace: last.Namespace,
			Chart:     last.Chart.Metadata.Name,
			Version:   last.Chart.Metadata.Version,
			Channel:   secret.Annotations[constant.AnnotationAppChannel],
			Username:  secret.Annotations[constant.AnnotationAppCreator],
			Values:    last.Config,
		}
		result = append(result, info)
	}

	return result, nil
}

func writeValuesFile(data map[string]interface{}) (string, error) {
	f, err := ioutil.TempFile("/tmp", "values.******.yaml")
	if err != nil {
		return "", err
	}
	vars, err := yaml.Marshal(data)
	if err != nil {
		return "nil", err
	}
	_, err = f.Write(vars)
	if err != nil {
		return "nil", err
	}
	_ = f.Close()
	return f.Name(), nil
}

func completeAppLabels(ctx context.Context, rel *release.Release, ks *cluster.Cluster, logger logrus.FieldLogger, meta metav1.ObjectMeta) error {
	logger.Info("start complete app labels")
	latestSecret, err := loadAppSecret(ctx, rel.Name, rel.Namespace, rel.Version, ks)
	if err != nil {
		return err
	}

	t := struct {
		Metadata metav1.ObjectMeta `json:"metadata"`
	}{meta}
	patchContent, _ := json.Marshal(&t)
	_, err = ks.Clients.Base.CoreV1().Secrets(latestSecret.Namespace).Patch(ctx, latestSecret.Name, types.MergePatchType, patchContent, metav1.PatchOptions{})
	return err
}

func loadAppSecret(ctx context.Context, name, namespace string, revision int, ks *cluster.Cluster) (*v1.Secret, error) {
	var targetSecret *v1.Secret

	selector := labels.Set{"name": name, "owner": "helm", "version": strconv.Itoa(revision)}.AsSelector()
	secrets, err := ks.Store.ListSecrets(namespace, selector)
	if err != nil {
		return nil, err
	}

	count := len(secrets)
	if count == 1 {
		targetSecret = secrets[0]
	}

	if targetSecret == nil {
		secretList, err := ks.Clients.Base.CoreV1().Secrets(namespace).List(ctx, metav1.ListOptions{LabelSelector: selector.String()})
		if err != nil {
			return nil, err
		}
		count = len(secretList.Items)
		if count != 1 {
			e := fmt.Errorf("get release secret failed, expect 1 got %d", count)
			return nil, e
		}
		targetSecret = &secretList.Items[0]
	}

	return targetSecret, nil
}
