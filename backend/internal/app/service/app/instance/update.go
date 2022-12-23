// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"os"
	"reflect"
	"strings"
	"time"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis"

	"helm.sh/helm/v3/pkg/release"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"

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
	if rel, err := h.Upgrade(i.name, helm.GenChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		return i.updateSecretMeta(rel)
	}
}

func (i *Instance) Suspend(chart, channel string) error {

	vals := i.release.Config

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	stopSettings := []string{"global.stopped=true", "global.suspended=true"}
	options := &values.Options{
		Values:     stopSettings,
		ValueFiles: []string{lastValFile},
	}

	h, _ := helm.NamespaceScope(i.namespace)
	if rel, err := h.Upgrade(i.name, helm.GenChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		return i.updateSecretMeta(rel)
	}
}

func (i *Instance) Start(chart, channel string, snippetSettings map[string]interface{}) error {
	h, _ := helm.NamespaceScope(i.namespace)
	vals := i.release.Config

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	startSettings := []string{"global.stopped=null", "global.suspended=null"}
	options := &values.Options{
		Values:     startSettings,
		ValueFiles: []string{lastValFile},
	}

	if len(snippetSettings) > 0 {
		snippetValueFile, err := writeValuesFile(snippetSettings)
		if err != nil {
			i.logger.WithError(err).Error("write values file failed")
		} else {
			defer os.Remove(snippetValueFile)
			options.ValueFiles = append(options.ValueFiles, snippetValueFile)
		}
	}

	if err = helm.RepoUpdate(); err != nil {
		return err
	}
	if rel, err := h.Upgrade(i.name, helm.GenChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		// add easyfost label for last secret
		return i.updateSecretMeta(rel)
	}
}

func (i *Instance) Restart(chart, channel string) error {

	vals := i.release.Config

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	stopSettings := generateRestartSetting()
	options := &values.Options{
		Values:     stopSettings,
		ValueFiles: []string{lastValFile},
	}

	h, _ := helm.NamespaceScope(i.namespace)
	if rel, err := h.Upgrade(i.name, helm.GenChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		return i.updateSecretMeta(rel)
	}
}

func generateRestartSetting() []string {
	uniqueStr := time.Now().Format(time.RFC3339)
	return []string{
		"global.env.RESTART_TIME=" + uniqueStr,
		"env.RESTART_TIME=null",
	}
}

func (i *Instance) PatchSettings(chart string, body model.AppCreateOrUpdateModel, snippetSettings, delSettings map[string]interface{}) error {
	var (
		err     error
		vals    map[string]interface{}
		version = i.CurrentChartVersion
	)

	i.logger.Debugf("delSettings is %+v", delSettings)

	h, _ := helm.NamespaceScope(i.namespace)
	vals, err = h.GetValues(i.name)
	if err != nil {
		return err
	}
	if vals == nil {
		vals = make(map[string]interface{})
	}

	// remove values from the release's current values
	if delSettings != nil {
		deleteValues("", delSettings, func(path string) {
			deletePath(vals, path)
		})
	}

	i.logger.Debugf("vals is %+v", vals)

	lastValFile, err := writeValuesFile(vals)
	if err != nil {
		return err
	}
	defer os.Remove(lastValFile)

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}

	if body.ForceRestart {
		settings = append(settings, generateRestartSetting()...)
	}

	options := &values.Options{
		Values:     settings,
		ValueFiles: []string{lastValFile},
	}

	i.logger.Debugf("options is %+v", options)

	if len(snippetSettings) > 0 {
		snippetValueFile, err := writeValuesFile(snippetSettings)
		if err != nil {
			i.logger.WithError(err).Error("write values file failed")
		} else {
			defer os.Remove(snippetValueFile)
			options.ValueFiles = append(options.ValueFiles, snippetValueFile)
		}
	}

	if len(body.SettingsMap) > 0 {
		i.logger.Infof("load patch settings map: %+v", body.SettingsMap)
		f, err := writeValuesFile(body.SettingsMap)
		if err != nil {
			i.logger.WithError(err).Error("write values file failed")
		} else {
			defer os.Remove(f)
			options.ValueFiles = append(options.ValueFiles, f)
		}
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
	if rel, err := h.Upgrade(i.name, helm.GenChart(body.Channel, chart), version, options); err != nil {
		analysis.Upgrade(chart, version).Fail(err)
		return err
	} else {
		analysis.Upgrade(chart, version).Success()
		return i.updateSecretMeta(rel)
	}
}

func (i *Instance) Uninstall() error {
	h, err := helm.NamespaceScope(i.namespace)
	if err != nil {
		return err
	}

	installTime := i.release.Info.FirstDeployed.Time
	uninstallTime := time.Now()

	dur := uninstallTime.Sub(installTime)

	err = h.Uninstall(i.name)
	if err != nil {
		analysis.UnInstall(i.ChartName, i.CurrentChartVersion).WithDuration(dur.Seconds()).Fail(err)
		return err
	}
	analysis.UnInstall(i.ChartName, i.CurrentChartVersion).WithDuration(dur.Seconds()).Success()
	return nil
}

func (i *Instance) updateSecretMeta(rel *release.Release) error {
	if !i.isApp() {
		return nil
	}
	secretMeta := metav1.ObjectMeta{
		Labels: map[string]string{
			constant.LabelApplication: "true",
		},
		Annotations: make(map[string]string),
	}
	if creator, ok := i.secret.Annotations[constant.AnnotationAppCreator]; ok {
		secretMeta.Annotations[constant.AnnotationAppCreator] = creator
	}
	if channel, ok := i.secret.Annotations[constant.AnnotationAppChannel]; ok {
		secretMeta.Annotations[constant.AnnotationAppChannel] = channel
	}

	err := completeAppLabels(i.Ctx, rel, i.Ks, i.logger, secretMeta)
	return err
}

func deleteValues(root string, delData map[string]interface{}, f func(path string)) {
	for key, value := range delData {
		var path string
		if root == "" {
			path = key
		} else {
			path = root + "." + key
		}

		vType := reflect.TypeOf(value)
		if vType.Kind() != reflect.Map {
			f(path)
		} else {
			deleteValues(path, value.(map[string]interface{}), f)
		}
	}
}

func deletePath(node map[string]interface{}, path string) bool {
	deleted := false
	frames := strings.Split(path, ".")
	if len(frames) > 1 {
		for _, frame := range frames[0 : len(frames)-1] {
			n, ok := node[frame]
			if !ok {
				break
			}
			ntype := reflect.TypeOf(n)
			if ntype.Kind() != reflect.Map {
				break
			}
			node = n.(map[string]interface{})
		}
		delete(node, frames[len(frames)-1])
		deleted = true
	} else {
		delete(node, path)
		deleted = true
	}
	return deleted
}
