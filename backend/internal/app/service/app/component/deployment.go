// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	metaappsv1 "k8s.io/api/apps/v1"
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

type Deployment struct {
	name   string
	kind   string
	object *metaappsv1.Deployment
	ks     *cluster.Cluster
}

func NewDeployComponent(obj *metaappsv1.Deployment, ks *cluster.Cluster) Component {
	return &Deployment{
		name: obj.Name, kind: KindDeployment,
		object: obj, ks: ks,
	}
}

func (d *Deployment) Name() string {
	return d.name
}

func (d *Deployment) Kind() string {
	return d.kind
}

func (d *Deployment) Replicas() int32 {
	return d.object.Status.Replicas
}

func (d *Deployment) Age() int64 {
	return parseOldestAge(d.getPods())
}

func (d *Deployment) Status(stopped bool) constant.AppStatusType {
	status := d.object.Status
	spec := d.object.Spec
	return parseStatus(*spec.Replicas, status.AvailableReplicas, status.UpdatedReplicas, status.ReadyReplicas, d.getPods(), stopped)
}

func (d *Deployment) getPods() []*v1.Pod {
	matchLabels := d.object.Spec.Selector.MatchLabels

	pods, _ := d.ks.Store.ListPods(d.object.Namespace, labels.SelectorFromValidatedSet(matchLabels))
	return pods
}
