// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package namespace

import (
	"context"

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
	logger      logrus.FieldLogger
}

func NewNamespaces(ctx context.Context, clusterName string) *Manager {
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName,
		ks:          cluster.Get(clusterName),
		logger:      logging.DefaultLogger().WithContext(ctx),
	}
}

func (m *Manager) Create(name string) error {
	newNS := &v1.Namespace{ObjectMeta: metav1.ObjectMeta{
		Name:        name,
		Labels:      map[string]string{labelCreatedBy: labelValueOwner},
		Annotations: map[string]string{},
	}}

	if _, err := m.ks.Clients.Base.CoreV1().Namespaces().Create(context.TODO(), newNS, metav1.CreateOptions{}); err != nil {
		return err
	}

	return nil
}

func (m *Manager) Recycle(name string) error {
	return m.ks.Clients.Base.CoreV1().Namespaces().Delete(context.TODO(), name, metav1.DeleteOptions{})
}

func (m *Manager) Has(name string) bool {
	_, err := m.ks.Clients.Base.CoreV1().Namespaces().Get(context.TODO(), name, metav1.GetOptions{})
	return err == nil
}

func (m *Manager) Get(name string) *Instance {
	ns, _ := m.ks.Clients.Base.CoreV1().Namespaces().Get(context.TODO(), name, metav1.GetOptions{})
	return newInstance(m.ctx, ns, m.ks)
}
