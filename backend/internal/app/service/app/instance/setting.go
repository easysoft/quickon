// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"encoding/json"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/parse"
	v1 "k8s.io/api/core/v1"
	"reflect"
	"strconv"
	"strings"

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"

	"github.com/imdario/mergo"
	"k8s.io/apimachinery/pkg/api/resource"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm/form"
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
	logger logrus.FieldLogger
}

func newSettings(app *Instance) *Settings {
	return &Settings{
		app: app, mode: listMode,
		logger: app.logger,
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
	if v, ok := vals["scalable"]; ok {
		data["scalable"] = v.(bool)
	} else {
		data["scalable"] = false
	}

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
			typeMEM := reflect.TypeOf(memory)
			var memStr string
			if typeMEM.Kind() == reflect.Float64 {
				memStr = strconv.Itoa(int(memory.(float64)))
			} else {
				memStr = memory.(string)
			}
			quanMemory, err := resource.ParseQuantity(memStr)
			if err == nil {
				resourceData["memory"], _ = quanMemory.AsInt64()
			}
		}
		data["resources"] = resourceData
	}

	oversold, ok := vals["oversold"]
	if ok {
		oversoldData := make(map[string]interface{})
		res := oversold.(map[string]interface{})
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
				oversoldData["cpu"] = float32(quanCpu.AsApproximateFloat64())
			}
		}

		if memory, ok := res["memory"]; ok {
			typeMEM := reflect.TypeOf(memory)
			var memStr string
			if typeMEM.Kind() == reflect.Float64 {
				memStr = strconv.Itoa(int(memory.(float64)))
			} else {
				memStr = memory.(string)
			}
			quanMemory, err := resource.ParseQuantity(memStr)
			if err == nil {
				oversoldData["memory"], _ = quanMemory.AsInt64()
			}
		}
		data["oversold"] = oversoldData
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

func (s *Settings) Custom() ([]model.AppCustomSetting, error) {
	vals, err := s.getMergedVals()
	if err != nil {
		return nil, err
	}
	settings := make([]model.AppCustomSetting, 0)
	if _customVals, ok := vals["_custom"]; ok {
		_cusVals, err := json.Marshal(_customVals)
		if err != nil {
			return nil, err
		}
		if err = json.Unmarshal(_cusVals, &settings); err != nil {
			return nil, err
		}

		customVals := make(map[string]interface{})
		if d, ok := vals["custom"]; ok {
			customVals = d.(map[string]interface{})
		}

		for id, setting := range settings {
			if setting.Label == "" {
				setting.Label = setting.Name
			}

			if currentValue, ok := customVals[setting.Name]; ok {
				setting.Default = currentValue
			}

			settings[id] = setting
		}

	}

	return settings, nil
}

// Mapping parse setting from helm values or secret
func (s *Settings) Mapping(items []model.AppSettingMappingItem) (map[string]string, error) {
	vals, err := s.getMergedVals()
	if err != nil {
		return nil, err
	}
	p := parse.New(vals)

	secrets, _ := s.app.Ks.Store.ListSecrets(s.app.namespace, s.app.selector)
	secretMap := mergeSecrets(secrets)

	var data = make(map[string]string)
	for _, m := range items {
		if m.Type == model.MappingKeyHelm {
			val, _ := p.GetString(m.Path)
			data[m.Key] = val
		} else if m.Type == model.MappingKeySecret {
			content, ok := secretMap[m.Path]
			if ok {
				data[m.Key] = string(content)
			} else {
				data[m.Key] = ""
			}
		}
	}
	return data, nil
}

func mergeSecrets(l []*v1.Secret) map[string][]byte {
	m := make(map[string][]byte, 0)
	for _, s := range l {
		for k, v := range s.Data {
			m[k] = v
		}
	}
	return m
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
	h := s.app.getHelmClient(s.app.namespace)

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
