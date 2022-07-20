// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	"context"
	"encoding/base64"
	"fmt"
	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"

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

func (m *Manager) ListDbsComponents(kind, namespace string) ([]model.ComponentDbServiceModel, error) {
	var components []model.ComponentDbServiceModel
	selector := labels.SelectorFromSet(map[string]string{
		constant.LabelGlobalDatabase: "true",
	})
	dbsvcs, err := m.ks.Store.ListDbService(namespace, selector)
	if err != nil {
		return nil, err
	}

	for _, dbsvc := range dbsvcs {
		if string(dbsvc.Spec.Type) != kind {
			continue
		}
		base := model.ComponentBase{
			Name:      dbsvc.Name,
			NameSpace: dbsvc.Namespace,
		}
		cm := model.ComponentDbServiceModel{
			Alias:      decodeDbSvcAlias(dbsvc),
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

func decodeDbSvcAlias(dbsvc *quchengv1beta1.DbService) string {
	alias := dbsvc.Annotations[constant.AnnotationResourceAlias]
	if alias == "" {
		return fmt.Sprintf("%s/%s", dbsvc.Namespace, dbsvc.Name)
	}

	bs, err := base64.StdEncoding.DecodeString(alias)
	if err != nil {
		return alias
	}

	return string(bs)
}
