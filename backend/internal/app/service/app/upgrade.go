// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"os"

	"helm.sh/helm/v3/pkg/cli/values"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

func (i *Instance) Stop(chart, channel string) error {

	vals := i.release.Config

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	stopSettings := []string{"global.stopped=true"}
	options := &values.Options{
		Values:     stopSettings,
		ValueFiles: []string{lastValFile},
	}

	h, _ := helm.NamespaceScope(i.namespace)
	if rel, err := h.Upgrade(i.name, genChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		if !i.isApp() {
			return nil
		}
		err = completeAppLabels(i.ctx, rel, i.ks, i.logger)
		return err
	}
}

func (i *Instance) Start(chart, channel string) error {
	h, _ := helm.NamespaceScope(i.namespace)
	vals := i.release.Config

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	startSettings := []string{"global.stopped=null"}
	options := &values.Options{
		Values:     startSettings,
		ValueFiles: []string{lastValFile},
	}

	if err = helm.RepoUpdate(); err != nil {
		return err
	}
	if rel, err := h.Upgrade(i.name, genChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		// add easyfost label for last secret
		if !i.isApp() {
			return nil
		}
		err = completeAppLabels(i.ctx, rel, i.ks, i.logger)
		return err
	}
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

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}

	options := &values.Options{
		Values:     settings,
		ValueFiles: []string{lastValFile},
	}

	if len(body.SettingsMap) > 0 {
		i.logger.Infof("load patch settings map: %+v", body.SettingsMap)
		f, err := writeValuesFile(body.SettingsMap)
		if err != nil {
			i.logger.WithError(err).Error("write values file failed")
		}
		defer os.Remove(f)
		options.ValueFiles = append(options.ValueFiles, f)
	}

	if err = h.PatchValues(vals, settings); err != nil {
		return err
	}

	if body.Version != "" {
		version = body.Version
	}
	if version != i.CurrentChartVersion {
		if err = helm.RepoUpdate(); err != nil {
			return err
		}
	}
	if rel, err := h.Upgrade(i.name, genChart(body.Channel, chart), version, options); err != nil {
		return err
	} else {
		if !i.isApp() {
			return nil
		}
		err = completeAppLabels(i.ctx, rel, i.ks, i.logger)
		return err
	}
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
