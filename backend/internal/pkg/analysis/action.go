// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package analysis

import "encoding/json"

type model struct {
	Name       string `json:"name"`
	Version    string `json:"version"`
	Action     string `json:"action"`
	Successful bool   `json:"success"`
	Err        string `json:"err"`

	Extra       string                 `json:"extra_properties"`
	extraSource map[string]interface{} `json:"-"`
}

func newModel(name, version, action string) model {
	return model{
		Name:        name,
		Version:     version,
		Action:      action,
		extraSource: make(map[string]interface{}),
	}
}

func (m *model) renderExtra() {
	if len(m.extraSource) > 0 {
		content, err := json.Marshal(m.extraSource)
		if err == nil {
			m.Extra = string(content)
		}
	}
}

func (m *model) Success() {
	m.Successful = true
	m.renderExtra()
	content, _ := json.Marshal(m)
	_analysis.write(string(content))
}

func (m *model) Fail(err error) {
	m.Successful = false
	m.Err = err.Error()
	m.renderExtra()
	content, _ := json.Marshal(m)
	_analysis.write(string(content))
}

type modelInstall struct {
	model
}

func Install(name, version string) *modelInstall {
	return &modelInstall{
		newModel(name, version, "install"),
	}
}

func (m *modelInstall) WithFeatures(feats []string) *model {
	m.model.extraSource["features"] = feats
	return &m.model
}

type modelUninstall struct {
	model
}

func UnInstall(name, version string) *modelUninstall {
	return &modelUninstall{
		newModel(name, version, "uninstall"),
	}
}

func (m *modelUninstall) WithDuration(dur float64) *model {
	m.model.extraSource["duration"] = dur
	return &m.model
}

type modelUpgrade struct {
	model
}

func Upgrade(name, version string) *modelUpgrade {
	return &modelUpgrade{
		newModel(name, version, "upgrade"),
	}
}
