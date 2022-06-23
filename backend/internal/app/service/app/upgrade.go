// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"github.com/imdario/mergo"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

func (i *Instance) Stop(chart, channel string) error {

	vars := i.release.Config

	updateMap := map[string]interface{}{
		"global": map[string]interface{}{
			"stopped": true,
		},
	}

	if err := mergo.Merge(&vars, updateMap, mergo.WithOverwriteWithEmptyValue); err != nil {
		return err
	}

	h, _ := helm.NamespaceScope(i.namespace)
	_, err := h.Upgrade(i.name, genChart(channel, chart), i.CurrentChartVersion, vars)
	return err
}

func (i *Instance) Start(chart, channel string) error {
	h, _ := helm.NamespaceScope(i.namespace)
	vars := i.release.Config

	globalVars, ok := vars["global"]
	if ok {
		globalVals := globalVars.(map[string]interface{})
		delete(globalVals, "stoped")
		delete(globalVals, "stopped")
		vars["global"] = globalVals
	}

	_, err := h.Upgrade(i.name, genChart(channel, chart), i.CurrentChartVersion, vars)
	return err
}

func (i *Instance) PatchSettings(chart string, body model.AppCreateOrUpdateModel) error {
	var (
		err     error
		vals    map[string]interface{}
		version = i.CurrentChartVersion
	)

	h, _ := helm.NamespaceScope(i.namespace)
	vals, err = h.GetValues(i.name)
	if err != nil {
		return err
	}

	if vals == nil {
		vals = make(map[string]interface{})
	}

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}

	if err = h.PatchValues(vals, settings); err != nil {
		return err
	}

	if body.Version != "" {
		version = body.Version
	}
	_, err = h.Upgrade(i.name, genChart(body.Channel, chart), version, vals)
	return err
}

func genRepo(channel string) string {
	c := "test"
	if channel != "" {
		c = channel
	}
	return "qucheng-" + c
}

func genChart(channel, chart string) string {
	return genRepo(channel) + "/" + chart
}
