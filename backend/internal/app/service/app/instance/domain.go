package instance

import (
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"strings"
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

func (i *Instance) GetDomains(component string) (interface{}, error) {
	ingresses, err := i.Ks.Store.ListIngresses(i.namespace, i.selector)
	if err != nil {
		return nil, err
	}

	var extraHosts = make(map[string]string, 0)
	var accessHost string
	var internalHost string

	filterComponentName := i.ChartName
	if component != "" {
		filterComponentName = component
	}

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
	}

	data := model.AppResDomain{
		InternalHost: internalHost,
		AccessHost:   accessHost,
		ExtraHosts:   extraHosts,
	}

	return data, nil
}
