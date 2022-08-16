package instance

import (
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

func (i *Instance) GetComponents() []model.Component {
	var components []model.Component

	components = append(components, model.Component{Name: i.ChartName})
	deps, err := helm.ParseChartDependencies(i.release.Chart)
	if err != nil {
		return components
	}

	for _, dep := range deps {
		components = append(components, model.Component{
			Name: dep.Name,
		})
	}

	return components
}

func (i *Instance) GetSchemaCategories(component string) interface{} {
	var data []string

	if component == i.ChartName {
		data = helm.ParseChartCategories(i.release.Chart)
	} else {
		var exist = false
		for _, dep := range i.release.Chart.Lock.Dependencies {
			if dep.Name == component {
				exist = true
				break
			}
		}

		if exist {
			ch, err := helm.GetChart(helm.GenChart("test", i.ChartName), i.CurrentChartVersion)
			if err != nil {
				return data
			}

			for _, dep := range ch.Dependencies() {
				if dep.Name() == component {
					data = helm.ParseChartCategories(dep)
					break
				}
			}
		}
	}

	return data
}

func (i *Instance) GetSchema(component, category string) string {
	var data string

	if component == i.ChartName {
		jbody, err := helm.ReadSchemaFromChart(i.release.Chart, category, "test")
		i.logger.Debugf("get schema content: %s", string(jbody))
		if err != nil {
			i.logger.WithError(err).Error("get schema failed")
			return ""
		}
		data = string(jbody)
	} else {
		ch, err := helm.GetChart(helm.GenChart("test", i.ChartName), i.CurrentChartVersion)
		if err != nil {
			return ""
		}
		for _, dep := range ch.Dependencies() {
			if dep.Name() == component {
				jbody, err := helm.ReadSchemaFromChart(dep, category, "test")
				if err != nil {
					return ""
				}
				data = string(jbody)
			}
		}
	}
	return data
}
