package instance

import (
	"reflect"
	"strconv"
	"strings"

	"github.com/imdario/mergo"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm/form"
	"k8s.io/apimachinery/pkg/api/resource"
)

type settingLayout int
type settingMode int

const (
	SimpleSettings settingLayout = iota + 1
	PatchSettings
	PublishSettings
)

const (
	listMode settingMode = iota + 1
	mapMode
)

type Settings struct {
	app    *Instance
	layout settingLayout
	mode   settingMode
}

func newSettings(app *Instance) *Settings {
	return &Settings{
		app: app, mode: listMode,
	}
}

func (s *Settings) Common() (map[string]interface{}, error) {
	vals, err := s.getMergedVals()
	if err != nil {
		s.app.logger.WithError(err).Error("prepare release values failed")
		return nil, err
	}
	data := make(map[string]interface{})
	data["replicas"] = vals["replicas"]

	resources, ok := vals["resources"]
	if ok {
		resourceData := make(map[string]interface{})
		res := resources.(map[string]interface{})
		if cpu, ok := res["cpu"]; ok {
			typeCPU := reflect.TypeOf(cpu)
			var cpuStr string
			if typeCPU.Kind() == reflect.Float64 {
				cpuStr = strconv.Itoa(int(cpu.(float64)))
			} else {
				cpuStr = cpu.(string)
			}
			quanCpu, err := resource.ParseQuantity(cpuStr)
			if err == nil {
				resourceData["cpu"] = float32(quanCpu.AsApproximateFloat64())
			}
		}

		if memory, ok := res["memory"]; ok {
			quanMemory, err := resource.ParseQuantity(memory.(string))
			if err == nil {
				resourceData["memory"], _ = quanMemory.AsInt64()
			}
		}
		data["resources"] = resourceData
	}

	ingress, ok := vals["ingress"]
	if ok {
		ingressData := make(map[string]interface{})
		ing := ingress.(map[string]interface{})
		ingressData["enabled"] = ing["enabled"]
		ingressData["host"] = ing["host"]
		data["ingress"] = ingressData
	}

	return data, nil
}

func (s *Settings) getMergedVals() (map[string]interface{}, error) {
	dst := s.app.release.Chart.Values
	if err := mergo.Merge(&dst, s.app.release.Config, mergo.WithOverride); err != nil {
		return nil, err
	}
	return dst, nil
}

func (s *Settings) Simple() *Settings {
	s.layout = SimpleSettings
	return s
}

func (s *Settings) Mode(m string) *Settings {
	if m == "list" {
		s.mode = listMode
	} else if m == "map" {
		s.mode = mapMode
	}
	return s
}

func (s *Settings) Parse() (interface{}, error) {
	h, err := helm.NamespaceScope(s.app.namespace)
	if err != nil {
		return nil, err
	}

	rel, err := h.GetRelease(s.app.name)
	if err != nil {
		return nil, err
	}

	vals := rel.Chart.Values
	if libVals, ok := vals["lib-common"]; ok {
		delete(vals, "lib-common")
		mergo.Merge(&vals, libVals, mergo.WithOverride)
	}

	err = mergo.Merge(&vals, rel.Config, mergo.WithOverride)
	if err != nil {
		return nil, err
	}

	form, err := helm.ParseForm(rel.Chart.Files)
	if err != nil {
		return nil, err
	}

	values := buildValuesMap(helm.ParseValues(vals))
	if s.mode == mapMode {
		return s.outputMap(values, form), nil
	}
	data := s.outputList(values, form)
	return data, nil
}

func (s *Settings) outputList(values map[string]string, form *form.DynamicForm) interface{} {
	var result []stringSetting
	if s.layout == SimpleSettings {
		for _, comp := range form.Sections {
			for _, group := range comp.Groups {
				for _, field := range group.Fields {
					if val, ok := values[field.Path]; ok {
						setting := stringSetting{Key: field.Path, Val: val}
						result = append(result, setting)
					}
				}
			}
		}
	}
	return result
}

func (s *Settings) outputMap(values map[string]string, form *form.DynamicForm) interface{} {
	var result = make(map[string]string, 0)
	if s.layout == SimpleSettings {
		for _, comp := range form.Sections {
			for _, group := range comp.Groups {
				for _, field := range group.Fields {
					if val, ok := values[field.Path]; ok {
						result[field.Path] = val
					}
				}
			}
		}
	}
	return result
}

func buildValuesMap(l [][2]string) map[string]string {
	var m = make(map[string]string)
	for _, item := range l {
		m[item[0]] = item[1]
	}
	return m
}

type stringSetting struct {
	Key string `json:"key"`
	Val string `json:"value"`
}

func (i *Instance) Settings() *Settings {
	return newSettings(i)
}

func (i *Instance) settingParse(path string) (interface{}, error) {
	var err error
	var ok bool
	var node map[string]interface{}
	var data interface{}

	frames := strings.Split(path, ".")
	node = i.release.Config

	if len(frames) > 1 {
		for _, frame := range frames[0 : len(frames)-1] {
			n, ok := node[frame]
			if !ok {
				err = ErrPathParseFailed
				break
			}
			ntype := reflect.TypeOf(n)
			if ntype.Kind() != reflect.Map {
				err = ErrPathParseFailed
				break
			}
			node = n.(map[string]interface{})
		}
		if err != nil {
			return nil, err
		}
		data, ok = node[frames[len(frames)-1]]
	} else {
		data, ok = node[path]
	}

	if !ok {
		return nil, ErrPathParseFailed
	}

	return data, nil
}
