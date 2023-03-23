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

type Statefulset struct {
	name   string
	kind   string
	object *metaappsv1.StatefulSet

	ks *cluster.Cluster
}

func NewStatefulsetComponent(obj *metaappsv1.StatefulSet, ks *cluster.Cluster) Component {
	return &Statefulset{
		name: obj.Name, kind: KindStatefulSet,
		object: obj, ks: ks,
	}
}

func (s *Statefulset) Name() string {
	return s.name
}

func (s *Statefulset) Kind() string {
	return s.kind
}

func (s *Statefulset) Replicas() int32 {
	return s.object.Status.Replicas
}

func (s *Statefulset) Age() int64 {
	return parseOldestAge(s.getPods())
}

func (s *Statefulset) Status(stopped bool) constant.AppStatusType {
	status := s.object.Status
	spec := s.object.Spec
	return parseStatus(*spec.Replicas, status.AvailableReplicas, status.UpdatedReplicas, status.ReadyReplicas, s.getPods(), stopped)
}

func (s *Statefulset) CompName() string {
	if name, ok := s.object.Labels[constant.LabelComponent]; ok {
		return name
	} else {
		return s.object.Labels[constant.LabelApp]
	}
}

func (s *Statefulset) Pods() []*v1.Pod {
	return s.getPods()
}

func (s *Statefulset) getPods() []*v1.Pod {
	matchLabels := s.object.Spec.Selector.MatchLabels

	pods, _ := s.ks.Store.ListPods(s.object.Namespace, labels.SelectorFromValidatedSet(matchLabels))
	return pods
}
