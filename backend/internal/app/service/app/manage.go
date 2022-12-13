// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"bytes"
	"compress/gzip"
	"context"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/utils/tls"
	"io"

	"github.com/sirupsen/logrus"
	"helm.sh/helm/v3/pkg/releaseutil"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app/instance"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"strconv"

	"helm.sh/helm/v3/pkg/release"
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/types"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
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

func (m *Manager) GetApp(name string) (*instance.Instance, error) {
	app, err := instance.NewInstance(m.ctx, name, m.clusterName, m.namespace, m.ks)
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
			m.logger.WithError(err).Errorf("patch app label failed, secret is %s", s.Name)
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

func (m *Manager) UploadTLS(name, certPem, keyPem string) error {
	secret, err := m.ks.Store.GetSecret(m.namespace, name)
	if err != nil {
		s := v1.Secret{
			ObjectMeta: metav1.ObjectMeta{
				Name: name,
			},
			Data: map[string][]byte{
				v1.TLSCertKey:       []byte(certPem),
				v1.TLSPrivateKeyKey: []byte(keyPem),
			},
			Type: v1.SecretTypeTLS,
		}
		_, err = m.ks.Clients.Base.CoreV1().Secrets(m.namespace).Create(m.ctx, &s, metav1.CreateOptions{})
		return err
	}

	secret.Data[v1.TLSCertKey] = []byte(certPem)
	secret.Data[v1.TLSPrivateKeyKey] = []byte(keyPem)

	_, err = m.ks.Clients.Base.CoreV1().Secrets(m.namespace).Update(m.ctx, secret, metav1.UpdateOptions{})
	return err
}

func (m *Manager) ReadTLSCertInfo(name string) (*tls.CertInfo, error) {
	secret, err := m.ks.Store.GetSecret(m.namespace, name)
	if err != nil {
		return nil, err
	}

	t, err := tls.Parse(secret.Data[v1.TLSCertKey], secret.Data[v1.TLSPrivateKeyKey])
	if err != nil {
		return nil, err
	}

	info := t.GetCertInfo()

	return &info, nil
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

/*
helm func
*/

var b64 = base64.StdEncoding

var magicGzip = []byte{0x1f, 0x8b, 0x08}

// decodeRelease decodes the bytes of data into a release
// type. Data must contain a base64 encoded gzipped string of a
// valid release, otherwise an error is returned.
func decodeRelease(data string) (*release.Release, error) {
	// base64 decode string
	b, err := b64.DecodeString(data)
	if err != nil {
		return nil, err
	}

	// For backwards compatibility with releases that were stored before
	// compression was introduced we skip decompression if the
	// gzip magic header is not found
	if bytes.Equal(b[0:3], magicGzip) {
		r, err := gzip.NewReader(bytes.NewReader(b))
		if err != nil {
			return nil, err
		}
		defer r.Close()
		b2, err := io.ReadAll(r)
		if err != nil {
			return nil, err
		}
		b = b2
	}

	var rls release.Release
	// unmarshal release object bytes
	if err := json.Unmarshal(b, &rls); err != nil {
		return nil, err
	}
	return &rls, nil
}
