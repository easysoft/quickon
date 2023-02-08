// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	batchv1 "k8s.io/api/batch/v1"
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

type BatchJob struct {
	name   string
	kind   string
	object *batchv1.Job
	ks     *cluster.Cluster
}

func NewJobComponent(obj *batchv1.Job, ks *cluster.Cluster) Component {
	return &BatchJob{
		name: obj.Name, kind: KindBatchJob,
		object: obj, ks: ks,
	}
}

func (d *BatchJob) Name() string {
	return d.name
}

func (d *BatchJob) Kind() string {
	return d.kind
}

func (d *BatchJob) Replicas() int32 {
	return 0
}

func (d *BatchJob) Age() int64 {
	return parseOldestAge(d.getPods())
}

func (d *BatchJob) Status(stopped bool) constant.AppStatusType {
	status := d.object.Status

	ret := constant.AppStatusStarting
	for _, cond := range status.Conditions {
		if cond.Type == batchv1.JobComplete && cond.Status == v1.ConditionTrue {
			ret = constant.AppStatusComplete
			break
		}
	}
	return ret
}

func (d *BatchJob) getPods() []*v1.Pod {
	matchLabels := d.object.Spec.Selector.MatchLabels

	pods, _ := d.ks.Store.ListPods(d.object.Namespace, labels.SelectorFromValidatedSet(matchLabels))
	return pods
}
