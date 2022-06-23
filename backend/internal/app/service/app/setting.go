// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"github.com/imdario/mergo"

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
}

func newSettings(app *Instance) *Settings {
	return &Settings{
		app: app, mode: listMode,
	}
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
