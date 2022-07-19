// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	"context"

	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
}

func NewComponents(ctx context.Context, clusterName string) *Manager {
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName,
		ks:          cluster.Get(clusterName),
	}
}

func (m *Manager) ListDbsComponents() ([]model.ComponentDbServiceModel, error) {
	var components []model.ComponentDbServiceModel
	dbsvcs, err := m.ks.Store.ListDbService("", labels.Everything())
	if err != nil {
		return nil, err
	}

	for _, dbsvc := range dbsvcs {
		base := model.ComponentBase{
			Name:      dbsvc.Name,
			NameSpace: dbsvc.Namespace,
		}
		cm := model.ComponentDbServiceModel{
			Spec:       dbsvc.Spec,
			Status:     dbsvc.Status,
			CreateTime: dbsvc.CreationTimestamp.Unix(),
			Source: model.ComponentBase{
				Name:      getSourceFromAnnotations(dbsvc.Annotations, "meta.helm.sh/release-name", "unknow"),
				NameSpace: getSourceFromAnnotations(dbsvc.Annotations, "meta.helm.sh/release-namespace", "default"),
			},
		}
		cm.ComponentBase = base
		components = append(components, cm)
	}
	return components, nil
}

func getSourceFromAnnotations(annotations map[string]string, key, value string) string {
	if source, ok := annotations[key]; ok {
		return source
	}
	return value
}
