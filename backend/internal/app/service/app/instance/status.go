// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	v1 "k8s.io/api/core/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app/component"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
)

func (i *Instance) getServices() ([]*v1.Service, error) {
	return i.Ks.Store.ListServices(i.namespace, i.selector)
}

func (i *Instance) getComponents() *component.Components {
	components := component.NewComponents()

	deployments, _ := i.Ks.Store.ListDeployments(i.namespace, i.selector)

	if len(deployments) >= 1 {
		for _, d := range deployments {
			components.Add(component.NewDeployComponent(d, i.Ks))
		}
	}

	statefulsets, _ := i.Ks.Store.ListStatefulSets(i.namespace, i.selector)

	if len(statefulsets) >= 1 {
		for _, s := range statefulsets {
			components.Add(component.NewStatefulsetComponent(s, i.Ks))
		}
	}

	jobs, _ := i.Ks.Store.ListJobs(i.namespace, i.selector)
	if len(jobs) >= 1 {
		for _, s := range jobs {
			components.Add(component.NewJobComponent(s, i.Ks))
		}
	}

	return components
}

func (i *Instance) ParseStatus() *model.AppRespStatus {
	var settingStopped = false
	var settingSuspended = false
	var minStatusCode constant.AppStatusType

	data := &model.AppRespStatus{
		Components: make([]model.AppRespStatusComponent, 0),
		Status:     constant.AppStatusMap[constant.AppStatusUnknown],
		Version:    i.CurrentChartVersion,
		Age:        0,
	}

	stopped, err := i.settingParse("global.stopped")
	if err == nil {
		if stop, ok := stopped.(bool); ok {
			settingStopped = stop
		}
	}

	suspended, err := i.settingParse("global.suspended")
	if err == nil {
		if s, ok := suspended.(bool); ok && settingStopped {
			settingSuspended = s
		}
	}

	if settingSuspended {
		minStatusCode = constant.AppStatusSuspended
		data.Status = constant.AppStatusMap[minStatusCode]
	} else if settingStopped {
		minStatusCode = constant.AppStatusStopped
		data.Status = constant.AppStatusMap[minStatusCode]
	}

	components := i.getComponents()
	// some apps will destroy workflows while stopped
	if len(components.Items()) == 0 {
		return data
	}

	for _, c := range components.Items() {
		status := c.Status(settingStopped)
		resC := model.AppRespStatusComponent{
			Name:       c.Name(),
			Kind:       c.Kind(),
			Replicas:   c.Replicas(),
			StatusCode: status,
			Status:     constant.AppStatusMap[status],
			Age:        c.Age(),
		}
		data.Components = append(data.Components, resC)
	}

	if minStatusCode == 0 {
		minStatusCode = data.Components[0].StatusCode
	}

	maxAge := data.Components[0].Age
	for _, comp := range data.Components {
		if comp.StatusCode < minStatusCode {
			minStatusCode = comp.StatusCode
		}

		if comp.Age > maxAge {
			maxAge = comp.Age
		}
	}

	data.Status = constant.AppStatusMap[minStatusCode]
	data.Age = maxAge
	return data
}

func (i *Instance) ParseNodePort() int32 {
	var nodePort int32 = 0
	services, err := i.getServices()
	if err != nil {
		return nodePort
	}

	for _, s := range services {
		if s.Spec.Type == v1.ServiceTypeNodePort {
			for _, p := range s.Spec.Ports {
				if p.Name == constant.ServicePortWeb || p.Name == "http" {
					nodePort = p.NodePort
					break
				}
			}
		}
	}

	return nodePort
}
