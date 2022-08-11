package instance

import (
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis"
	"os"
	"time"

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
	if rel, err := h.Upgrade(i.name, helm.GenChart(channel, chart), i.CurrentChartVersion, options); err != nil {
		return err
	} else {
		// add easyfost label for last secret
		return i.updateSecretMeta(rel)
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
	if rel, err := h.Upgrade(i.name, helm.GenChart(body.Channel, chart), version, options); err != nil {
		analysis.Upgrade(body.Chart, version).Fail(err)
		return err
	} else {
		analysis.Upgrade(body.Chart, version).Success()
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
