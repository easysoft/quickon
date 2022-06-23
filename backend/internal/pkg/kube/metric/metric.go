// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package metric

import (
	"context"
	v1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/client-go/rest"
	"k8s.io/metrics/pkg/apis/metrics/v1beta1"
	"k8s.io/metrics/pkg/client/clientset/versioned"
)

type Metric struct {
	client  *versioned.Clientset
	enabled bool
}

func NewMetric(config rest.Config) *Metric {
	c := config
	c.ContentType = "application/vnd.kubernetes.protobuf"
	client, err := versioned.NewForConfig(&c)
	return &Metric{
		client: client, enabled: err != nil,
	}
}

func (m *Metric) GetPodMetric(ctx context.Context, namespace, name string) (*Res, error) {
	metric, err := m.client.MetricsV1beta1().PodMetricses(namespace).Get(ctx, name, v1.GetOptions{})
	if err != nil {
		return nil, err
	}

	return sumPodMetric(*metric), nil
}

func (m *Metric) ListPodMetrics(ctx context.Context, namespace string, selector labels.Selector) []*Res {
	var result []*Res

	metrics, err := m.client.MetricsV1beta1().PodMetricses(namespace).List(ctx, v1.ListOptions{LabelSelector: selector.String()})
	if err != nil {
		return result
	}

	for _, pm := range metrics.Items {
		result = append(result, sumPodMetric(pm))
	}

	return result
}

func (m *Metric) ListNodeMetrics(ctx context.Context) []*Res {
	var result []*Res

	metrics, err := m.client.MetricsV1beta1().NodeMetricses().List(ctx, v1.ListOptions{})
	if err != nil {
		return result
	}

	for _, nm := range metrics.Items {
		result = append(result, &Res{
			Cpu: nm.Usage.Cpu(), Memory: nm.Usage.Memory(),
		})
	}
	return result
}

func sumPodMetric(pm v1beta1.PodMetrics) *Res {
	var m Res
	count := len(pm.Containers)
	if count >= 1 {
		m.Cpu = pm.Containers[0].Usage.Cpu()
		m.Memory = pm.Containers[0].Usage.Memory()
	}
	for _, cm := range pm.Containers[1:] {
		m.Cpu.Add(cm.Usage.Cpu().DeepCopy())
		m.Memory.Add(cm.Usage.Memory().DeepCopy())
	}
	return &m
}
