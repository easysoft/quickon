// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/api/resource"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/metric"
)

func (i *Instance) GetMetrics() *model.AppMetric {
	metrics := i.Ks.Metric.ListPodMetrics(i.Ctx, i.namespace, i.selector)
	pods, _ := i.Ks.Store.ListPods(i.namespace, i.selector)

	var usage metric.Res
	var limit metric.Res

	sumPodUsage(&usage, metrics)
	sumPodLimit(&limit, pods)

	memUsage, _ := usage.Memory.AsInt64()
	memLimit, _ := limit.Memory.AsInt64()

	data := model.AppMetric{
		Cpu: model.ResourceCpu{
			Usage: usage.Cpu.AsApproximateFloat64(), Limit: limit.Cpu.AsApproximateFloat64(),
		},
		Memory: model.ResourceMemory{
			Usage: memUsage, Limit: memLimit,
		},
	}
	return &data
}

func sumPodUsage(dst *metric.Res, metrics []*metric.Res) {
	dst.Cpu = resource.NewQuantity(0, resource.DecimalExponent)
	dst.Memory = resource.NewQuantity(0, resource.DecimalExponent)

	for _, m := range metrics {
		dst.Cpu.Add(m.Cpu.DeepCopy())
		dst.Memory.Add(m.Memory.DeepCopy())
	}
}

func sumPodLimit(dst *metric.Res, pods []*v1.Pod) {
	dst.Cpu = resource.NewQuantity(0, resource.DecimalExponent)
	dst.Memory = resource.NewQuantity(0, resource.DecimalExponent)

	for _, pod := range pods {
		for _, ctn := range pod.Spec.Containers {
			l := ctn.Resources.Limits
			dst.Cpu.Add(*l.Cpu())
			dst.Memory.Add(*l.Memory())
		}
	}
}
