// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package namespace

import (
	"context"

	"github.com/sirupsen/logrus"
	v1 "k8s.io/api/core/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

type Instance struct {
	ctx       context.Context
	namespace string
	ns        *v1.Namespace
	ks        *cluster.Cluster
	logger    logrus.FieldLogger
}

func newInstance(ctx context.Context, ns *v1.Namespace, ks *cluster.Cluster) *Instance {
	return &Instance{
		ctx:       ctx,
		namespace: ns.Name,
		ns:        ns,
		ks:        ks,
		logger:    logging.DefaultLogger().WithContext(ctx).WithField("namespace", ns),
	}
}

func (i *Instance) WriteAble() bool {
	labels := i.ns.Labels
	if v, ok := labels[labelCreatedBy]; ok {
		return v == labelValueOwner
	}
	return false
}
