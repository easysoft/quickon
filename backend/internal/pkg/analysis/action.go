package analysis

import "encoding/json"

type model struct {
	Name       string `json:"name"`
	Version    string `json:"version"`
	Action     string `json:"action"`
	Successful bool   `json:"success"`
	Err        string `json:"err"`

	Extra map[string]interface{} `json:"extraProperties"`
}

func newModel(name, version, action string) model {
	return model{
		Name:    name,
		Version: version,
		Action:  action,
		Extra:   make(map[string]interface{}),
	}
}

func (m *model) Success() {
	m.Successful = true
	content, _ := json.Marshal(m)
	_analysis.write(string(content))
}

func (m *model) Fail(err error) {
	m.Successful = false
	m.Err = err.Error()
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

func (m *modelInstall) AddFeature(feats ...string) *model {
	m.model.Extra["features"] = feats
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
	m.model.Extra["duration"] = dur
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

func (m *modelUpgrade) WithVersion(version string) *model {
	m.model.Extra["upgrade_to"] = version
	return &m.model
}
