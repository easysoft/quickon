// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package snippet

import (
	"context"
	"fmt"

	"sigs.k8s.io/yaml"

	"github.com/sirupsen/logrus"
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
	logger      logrus.FieldLogger
}

func NewSnippets(ctx context.Context, clusterName string) *Manager {
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName,
		ks:          cluster.Get(clusterName),
		logger:      logging.DefaultLogger().WithContext(ctx),
	}
}

func (m *Manager) List(namespace string) ([]*model.SnippetConfig, error) {
	var data []*model.SnippetConfig
	cmList, err := m.ks.Store.ListConfigMaps(namespace, labels.Set{LabelSnippetConfig: "true"}.AsSelector())
	if err != nil {
		return data, err
	}

	for _, cm := range cmList {
		snippet, err := NewSnippet(cm)
		if err != nil {
			m.logger.WithError(err).Error("convert configmap to snippet failed")
			continue
		}
		sc := &model.SnippetConfig{
			Name:       cm.Name,
			Namespace:  cm.Namespace,
			Category:   snippet.Category(),
			Values:     snippet.Values(),
			AutoImport: snippet.IsAutoImport(),
		}
		data = append(data, sc)
	}

	return data, nil
}

func (m *Manager) Get(name, namespace string) (*Snippet, error) {
	cm, err := m.ks.Store.GetConfigMap(namespace, name)
	if err != nil {
		return nil, err
	}

	s, err := NewSnippet(cm)
	return s, err
}

func (m *Manager) Create(name, namespace, category string, values map[string]interface{}, autoImport bool) error {
	content, err := yaml.Marshal(values)
	if err != nil {
		return err
	}

	cm := v1.ConfigMap{
		ObjectMeta: metav1.ObjectMeta{
			Name: name, Namespace: namespace,
			Labels: labels.Set{
				LabelSnippetConfig: "true",
			},
			Annotations: make(map[string]string),
		},
		Data: map[string]string{
			snippetContentKey: string(content),
		},
	}
	if autoImport {
		cm.Annotations[annotationSnippetConfigAutoImport] = "true"
	}
	if category != "" {
		cm.Annotations[annotationSnippetConfigCategory] = category
	}

	_, err = m.ks.Clients.Base.CoreV1().ConfigMaps(namespace).Create(m.ctx, &cm, metav1.CreateOptions{})
	return err
}

func (m *Manager) Update(name, namespace, category string, values map[string]interface{}, autoImport bool) error {
	cm, err := m.ks.Store.GetConfigMap(namespace, name)
	if err != nil {
		return err
	}

	content, err := yaml.Marshal(values)
	if err != nil {
		return err
	}
	cm.Data[snippetContentKey] = string(content)
	cm.Annotations[annotationSnippetConfigAutoImport] = fmt.Sprintf("%v", autoImport)
	if category != "" {
		cm.Annotations[annotationSnippetConfigCategory] = category
	}
	_, err = m.ks.Clients.Base.CoreV1().ConfigMaps(namespace).Update(m.ctx, cm, metav1.UpdateOptions{})
	return err
}

func (m *Manager) Remove(name, namespace string) error {
	return m.ks.Clients.Base.CoreV1().ConfigMaps(namespace).Delete(m.ctx, name, metav1.DeleteOptions{})
}
