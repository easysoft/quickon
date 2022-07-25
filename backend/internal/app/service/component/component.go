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
	"k8s.io/apimachinery/pkg/util/intstr"

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
		hp, err := m.parseDbSvcDetail(dbsvc)
		if err != nil {
			continue
		}
		cm := model.ComponentDbServiceModel{
			ComponentBase: model.ComponentBase{
				Name: dbsvc.Name, NameSpace: dbsvc.Namespace,
			},
			Alias: decodeDbSvcAlias(dbsvc),
			Host:  hp.Host,
			Port:  hp.Port,
		}
		components = append(components, cm)
	}
	return components, nil
}

func (m *Manager) parseDbSvcDetail(dbsvc *quchengv1beta1.DbService) (*hostPort, error) {
	svc := dbsvc.Spec.Service
	svcNs := svc.Namespace
	if svcNs == "" {
		svcNs = dbsvc.Namespace
	}
	host := fmt.Sprintf("%s.%s.svc", svc.Name, svcNs)

	var port int32
	if svc.Port.Type == intstr.Int {
		port = svc.Port.IntVal
	} else {
		s, err := m.ks.Store.GetService(svcNs, svc.Name)
		if err != nil {
			return nil, err
		}
		for _, p := range s.Spec.Ports {
			if p.Name == svc.Port.StrVal {
				port = p.Port
				break
			}
		}
		if port == 0 {
			return nil, fmt.Errorf("parse service port '%s' failed", svc.Port.StrVal)
		}
	}
	return &hostPort{
		Host: host, Port: port,
	}, nil
}

type hostPort struct {
	Host string
	Port int32
}

func (m *Manager) ValidDbService(name, namespace, dbname, username string) (interface{}, error) {
	result := &model.ComponentDbServiceValidResult{
		Validation: model.DbValidation{},
	}
	dbsvc, err := m.ks.Store.GetDbService(namespace, name)
	if err != nil {
		return nil, err
	}

	dbValid := model.DbValidation{User: true, Database: true}
	allDbs, err := m.ks.Store.ListDb("", labels.Everything())
	for _, db := range allDbs {
		dbNs := db.Spec.TargetService.Namespace
		if dbNs == "" {
			dbNs = db.Namespace
		}
		if dbNs != dbsvc.Namespace || db.Spec.TargetService.Name != dbsvc.Name {
			continue
		}

		if dbValid.Database && db.Spec.DbName == dbname {
			dbValid.Database = false
		}

		if dbValid.User && db.Spec.Account.User.Value == username {
			dbValid.User = false
		}

		if !dbValid.Database && !dbValid.User {
			break
		}
	}
	result.Validation = dbValid

	svc := dbsvc.Spec.Service
	svcNs := svc.Namespace
	if svcNs == "" {
		svcNs = dbsvc.Namespace
	}
	result.Host = fmt.Sprintf("%s.%s.svc", svc.Name, svcNs)

	var port int32
	if svc.Port.Type == intstr.Int {
		port = svc.Port.IntVal
	} else {
		s, err := m.ks.Store.GetService(svcNs, svc.Name)
		if err != nil {
			return nil, err
		}
		for _, p := range s.Spec.Ports {
			if p.Name == svc.Port.StrVal {
				port = p.Port
				break
			}
		}
		if port == 0 {
			return nil, fmt.Errorf("parse service port '%s' failed", svc.Port.StrVal)
		}
	}

	result.Port = port
	return result, nil
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
