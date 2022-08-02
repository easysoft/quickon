// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"context"

	"fmt"
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"gopkg.in/yaml.v3"
	"helm.sh/helm/v3/pkg/cli/values"
	"helm.sh/helm/v3/pkg/release"
	"io/ioutil"
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/types"
	"os"
	"strconv"

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
		if _, e := h.GetRelease(name); e == nil {
			logger.Info("recycle incomplete release")
			_ = h.Uninstall(name)
		}
	}
	err = completeAppLabels(m.ctx, rel, m.ks, logger)
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

	app.prepare()
	return app, nil
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

func completeAppLabels(ctx context.Context, rel *release.Release, ks *cluster.Cluster, logger logrus.FieldLogger) error {
	var targetSecret *v1.Secret

	logger.Info("start complete app labels")

	selector := labels.Set{"name": rel.Name, "owner": "helm", "version": strconv.Itoa(rel.Version)}.AsSelector()
	secrets, err := ks.Store.ListSecrets(rel.Namespace, selector)
	if err != nil {
		return err
	}

	count := len(secrets)
	if count == 1 {
		targetSecret = secrets[0]
		logger.Info("find release secret from informer cache")
	}

	if targetSecret == nil {
		secretList, err := ks.Clients.Base.CoreV1().Secrets(rel.Namespace).List(ctx, metav1.ListOptions{LabelSelector: selector.String()})
		if err != nil {
			return err
		}
		count = len(secretList.Items)
		if count != 1 {
			e := fmt.Errorf("get release secret failed, expect 1 got %d", count)
			return e
		}
		targetSecret = &secretList.Items[0]
		logger.Info("find release secret from apiserver")
	}

	patchContent := fmt.Sprintf(`{"metadata":{"labels":{"%s":"true"}}}`, constant.LabelApplication)
	logger.Infof("patch content is %s", patchContent)
	_, err = ks.Clients.Base.CoreV1().Secrets(targetSecret.Namespace).Patch(ctx, targetSecret.Name, types.MergePatchType, []byte(patchContent), metav1.PatchOptions{})
	return err
}
