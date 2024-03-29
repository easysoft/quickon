// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	"context"
	"encoding/base64"
	"fmt"

	quchengv1beta1 "github.com/easysoft/quickon-api/qucheng/v1beta1"
	"github.com/sirupsen/logrus"
	apierrors "k8s.io/apimachinery/pkg/api/errors"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/apis/meta/v1/unstructured"
	"k8s.io/apimachinery/pkg/runtime/schema"
	"k8s.io/apimachinery/pkg/util/intstr"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
	logger      logrus.FieldLogger
}

func NewComponents(ctx context.Context, clusterName string) *Manager {
	ks := cluster.Get(clusterName)
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName,
		ks:          ks,
		logger:      logging.DefaultLogger().WithContext(ctx).WithField("cluster", ks.Name),
	}
}

func (m *Manager) ListDbService(namespace string, onlyGlobal string) ([]model.ComponentDbService, error) {
	var components []model.ComponentDbService
	GlobalDbSvsList, err := m.ks.Store.ListDbService("", labels.Set{constant.LabelGlobalDatabase: "true"}.AsSelector())
	if err != nil {
		return components, err
	}

	for _, dbSvc := range GlobalDbSvsList {
		s := model.ComponentDbService{
			ComponentBase: model.ComponentBase{Name: dbSvc.Name, NameSpace: dbSvc.Namespace},
			Release:       dbSvc.Labels["release"],
			DbType:        string(dbSvc.Spec.Type),
			Alias:         decodeDbSvcAlias(dbSvc),
			Ready:         fromBool(dbSvc.Status.Ready),
		}
		components = append(components, s)
	}

	if onlyGlobal == "false" {
		dbSvsList, err := m.ks.Store.ListDbService(namespace, labels.Everything())
		if err != nil {
			return components, err
		}

		for _, dbSvc := range dbSvsList {
			s := model.ComponentDbService{
				ComponentBase: model.ComponentBase{Name: dbSvc.Name, NameSpace: dbSvc.Namespace},
				Release:       dbSvc.Labels["release"],
				DbType:        string(dbSvc.Spec.Type),
				Alias:         decodeDbSvcAlias(dbSvc),
				Ready:         fromBool(dbSvc.Status.Ready),
			}
			components = append(components, s)
		}
	}

	return components, nil
}

func (m *Manager) GetDbServiceDetail(name, namespace string) (interface{}, error) {
	dbSvc, err := m.ks.Store.GetDbService(namespace, name)
	if err != nil {
		return nil, err
	}

	hp, err := m.parseDbSvcDetail(dbSvc)
	if err != nil {
		return nil, err
	}

	user := dbSvc.Spec.Account.User.Value
	passwd := dbSvc.Spec.Account.Password.Value
	if passwd == "" {
		secretRef := dbSvc.Spec.Account.Password.ValueFrom.SecretKeyRef
		if secretRef != nil {
			secret, err := m.ks.Store.GetSecret(dbSvc.Namespace, secretRef.Name)
			if err != nil {
				return nil, err
			}
			passwd = string(secret.Data[secretRef.Key])
		}
	}

	data := model.ComponentDbServiceDetail{
		ComponentBase: model.ComponentBase{Name: dbSvc.Name, NameSpace: dbSvc.Namespace},
		Host:          hp.Host,
		Port:          hp.Port,
		UserName:      user,
		Password:      passwd,
		Database:      "",
	}

	return data, nil
}

func (m *Manager) ListGlobalDbsComponents(kind, namespace string) ([]model.ComponentGlobalDbServiceModel, error) {
	var components []model.ComponentGlobalDbServiceModel
	selector := labels.SelectorFromSet(map[string]string{
		constant.LabelGlobalDatabase: "true",
	})
	dbsvcs, err := m.ks.Store.ListDbService("", selector)
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
		cm := model.ComponentGlobalDbServiceModel{
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
	allDbs, _ := m.ks.Store.ListDb("", labels.Everything())
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
		return ""
	}

	bs, err := base64.StdEncoding.DecodeString(alias)
	if err != nil {
		return alias
	}

	return string(bs)
}

func fromBool(b *bool) bool {
	if b == nil {
		return false
	}
	return *b
}

// CreateQLBConfig 更新负载均衡配置, 若没有则配置
func (m *Manager) UpdateQLBConfig(name, namespace, ippool string) error {
	gvr := schema.GroupVersionResource{Group: "metallb.io", Version: "v1beta1", Resource: "ipaddresspools"}
	dc := m.ks.Store.Clients.Dynamic.Resource(gvr).Namespace(namespace)
	found, err := dc.Get(context.TODO(), name, metav1.GetOptions{})
	if err != nil {
		if apierrors.IsNotFound(err) {
			object := &unstructured.Unstructured{
				Object: map[string]interface{}{
					"apiVersion": "metallb.io/v1beta1",
					"kind":       "IPAddressPool",
					"metadata": map[string]interface{}{
						"name":      name,
						"namespace": namespace,
					},
					"spec": map[string]interface{}{
						"addresses": []interface{}{
							ippool,
						},
					},
				},
			}
			_, err = dc.Create(context.TODO(), object, metav1.CreateOptions{})
			return err
		}
		return err
	}
	// 存在更新
	newipPool := []interface{}{
		ippool,
	}
	if err := unstructured.SetNestedSlice(found.Object, newipPool, "spec", "addresses"); err != nil {
		return err
	}
	_, err = dc.Update(context.TODO(), found, metav1.UpdateOptions{})
	return err
}

// GetQLBConfig 查询负载均衡配置
func (m *Manager) GetQLBConfig(name, namespace string) (string, error) {
	gvr := schema.GroupVersionResource{Group: "metallb.io", Version: "v1beta1", Resource: "ipaddresspools"}
	dc := m.ks.Store.Clients.Dynamic.Resource(gvr).Namespace(namespace)
	found, err := dc.Get(context.TODO(), name, metav1.GetOptions{})
	if err != nil {
		return "", err
	}
	data, exist, err := unstructured.NestedSlice(found.Object, "spec", "addresses")
	if err != nil {
		return "", err
	}
	if !exist || len(data) == 0 {
		return "", fmt.Errorf("not exist addresses filed")
	}
	return data[0].(string), nil
}
