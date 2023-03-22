// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"strings"

	v1 "k8s.io/api/core/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
)

func (i *Instance) ListIngressHosts() []string {
	var hosts []string
	ingresses, err := i.Ks.Store.ListIngresses(i.namespace, i.selector)
	if err != nil {
		return hosts
	}
	for _, ing := range ingresses {
		for _, rule := range ing.Spec.Rules {
			hosts = append(hosts, rule.Host)
		}
	}
	return hosts
}

/*
GetDomains support parse multi ingress of a release.
parse the internal host, web access host, and the other hosts.
parameter component default to the chart's Name,
if the chart contains multi component, or have subCharts, use component to filter.
if a component has multi ingresses, only one ingress without the special annotation will be parsed as access host,
the others should define ingress.annotations.
currently, only support one ingress with one host.
*/
func (i *Instance) GetDomains(component string) (interface{}, error) {
	ingresses, err := i.Ks.Store.ListIngresses(i.namespace, i.selector)
	if err != nil {
		return nil, err
	}

	var extraHosts = make(map[string]string, 0)
	var lbIps = make(map[string]string, 0)
	var accessHost string
	var internalHost string

	// use chart name as default component
	filterComponentName := i.ChartName
	if component != "" {
		filterComponentName = component
	}

	// parse access host and extra hosts.
	for _, ing := range ingresses {
		componentName := ing.Labels[constant.LabelApp]
		if labelComponent, exist := ing.ObjectMeta.Labels[constant.LabelComponent]; exist {
			componentName = labelComponent
		}

		if len(ing.Spec.Rules) == 0 {
			i.logger.WithField("ingress", ing.Name).Info("no hosts found in ingress spec")
			continue
		}

		ingHost := ing.Spec.Rules[0].Host
		domainKey, domainKeyExist := ing.Annotations[constant.AnnotationAppDomainKey]

		if componentName == filterComponentName {
			if domainKeyExist {
				extraHosts[domainKey] = ingHost
			} else {
				if accessHost == "" {
					accessHost = ingHost
				}
			}
		} else {
			if domainKeyExist {
				extraHosts[domainKey] = ingHost
			}
		}
	}

	// parse internal host
	services, err := i.getServices()
	if err != nil {
		return nil, err
	}

	for _, svc := range services {
		componentName := svc.Labels[constant.LabelApp]
		if labelComponent, exist := svc.ObjectMeta.Labels[constant.LabelComponent]; exist {
			componentName = labelComponent
		}

		if componentName == filterComponentName {
			internalHost = strings.Join([]string{svc.Name, svc.Namespace, "svc"}, ".")
		}

		if svc.Spec.Type == v1.ServiceTypeLoadBalancer {
			if len(svc.Status.LoadBalancer.Ingress) > 0 {
				lbIps[componentName] = svc.Status.LoadBalancer.Ingress[0].IP
			}
		}
	}

	// build response
	data := model.AppResDomain{
		InternalHost:    internalHost,
		AccessHost:      accessHost,
		ExtraHosts:      extraHosts,
		LoadBalancerIPS: lbIps,
	}

	return data, nil
}
