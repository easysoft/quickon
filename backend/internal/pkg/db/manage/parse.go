// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package manage

import (
	"context"
	"fmt"

	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
	"github.com/pkg/errors"
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/util/intstr"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/store"
)

func ParseDB(ctx context.Context, c *store.Storer, db *quchengv1beta1.Db) (DbManager, *DbMeta, error) {
	var err error
	var dbSvc *quchengv1beta1.DbService
	var dbMgr DbManager
	var dbMeta *DbMeta

	target := db.Spec.TargetService
	ns := target.Namespace
	if ns == "" {
		ns = db.Namespace
	}

	dbSvc, err = c.GetDbService(ns, target.Name)
	if err != nil {
		return nil, nil, err
	}
	dbMgr, err = ParseDbService(ctx, c, dbSvc)
	if err != nil {
		return nil, nil, err
	}

	dbUser, dbPass, err := readAccount(ctx, c, db.Namespace, &db.Spec.Account)
	if err != nil {
		return nil, nil, err
	}
	dbMeta = &DbMeta{
		Name:     db.Spec.DbName,
		User:     dbUser,
		Password: dbPass,
	}

	return dbMgr, dbMeta, nil
}

func ParseDbService(ctx context.Context, c *store.Storer, dbSvc *quchengv1beta1.DbService) (DbManager, error) {
	svc := dbSvc.Spec.Service
	host, port, err := readService(ctx, c, dbSvc.Namespace, &svc)
	if err != nil {
		return nil, err
	}

	dbUser, dbPass, err := readAccount(ctx, c, dbSvc.Namespace, &dbSvc.Spec.Account)
	if err != nil {
		return nil, err
	}

	meta := DbServiceMeta{
		Type:          dbSvc.Spec.Type,
		Host:          host,
		Port:          port,
		AdminUser:     dbUser,
		AdminPassword: dbPass,
	}

	switch meta.Type {
	case quchengv1beta1.DbTypeMysql:
		return newMysqlManager(meta)
	case quchengv1beta1.DbTypePostgresql:
		return newPostgresqlManager(meta)
	default:
		return nil, errors.New("dbType is not supported")
	}
}

func readService(ctx context.Context, c *store.Storer, namespace string, svc *quchengv1beta1.Service) (string, int32, error) {
	targetNs := svc.Namespace
	if targetNs == "" {
		targetNs = namespace
	}
	host := fmt.Sprintf("%s.%s.svc", svc.Name, targetNs)

	var (
		port int32
		err  error
		s    *v1.Service
	)

	if svc.Port.Type == intstr.Int {
		port = svc.Port.IntVal
	} else {
		s, err = c.GetService(targetNs, svc.Name)
		if err != nil {
			return "", 0, err
		}
		for _, p := range s.Spec.Ports {
			if p.Name == svc.Port.StrVal {
				port = p.Port
				break
			}
		}
	}

	if port == 0 {
		return "", 0, fmt.Errorf("parse service port '%s' failed", svc.Port.StrVal)
	}

	return host, port, nil
}

func readAccount(ctx context.Context, c *store.Storer, namespace string, info *quchengv1beta1.Account) (string, string, error) {
	user, err := kube.ReadValueSource(c, namespace, kube.NewValueRef(info.User.Value, info.User.ValueFrom))
	if err != nil {
		return "", "", err
	}
	passwd, err := kube.ReadValueSource(c, namespace, kube.NewValueRef(info.Password.Value, info.Password.ValueFrom))
	if err != nil {
		return "", "", err
	}
	return user, passwd, nil
}
