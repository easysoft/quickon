package app

import (
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/utils/kvpath"
	"gopkg.in/yaml.v3"
	"helm.sh/helm/v3/pkg/cli/values"
	"io/ioutil"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"os"
)

func (m *Manager) Install(name string, body model.AppCreateOrUpdateModel, snippetSettings map[string]interface{}) error {
	logger := m.logger.WithFields(logrus.Fields{
		"name": name, "namespace": body.Namespace,
	})
	h, err := helm.NamespaceScope(m.namespace)
	if err != nil {
		return err
	}

	options := &values.Options{ValueFiles: make([]string, 0)}

	if len(snippetSettings) > 0 {
		snippetValueFile, err := writeValuesFile(snippetSettings)
		if err != nil {
			logger.WithError(err).Error("write values file failed")
		} else {
			defer os.Remove(snippetValueFile)
			options.ValueFiles = append(options.ValueFiles, snippetValueFile)
		}
	}

	if len(body.SettingsMap) > 0 {
		logger.Infof("user custom settingsMap is %+v", body.SettingsMap)
		f, err := writeValuesFile(body.SettingsMap)
		if err != nil {
			logger.WithError(err).Error("write values file failed")
		} else {
			defer os.Remove(f)
			options.ValueFiles = append(options.ValueFiles, f)
		}
	}

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}
	options.Values = settings
	logger.Infof("user custom settings is %+v", settings)

	if err = helm.RepoUpdate(); err != nil {
		logger.WithError(err).Error("helm update repo failed")
		return err
	}

	feats := parseFeatures(body.SettingsMap)
	rel, err := h.Install(name, helm.GenChart(body.Channel, body.Chart), body.Version, options)
	if err != nil {
		logger.WithError(err).Error("helm install failed")
		analysis.Install(body.Chart, body.Version).WithFeatures(feats).Fail(err)
		if _, e := h.GetRelease(name); e == nil {
			logger.Info("recycle incomplete release")
			_ = h.Uninstall(name)
		}
		return err
	}
	secretMeta := metav1.ObjectMeta{
		Labels: map[string]string{
			constant.LabelApplication: "true",
		},
		Annotations: map[string]string{
			constant.AnnotationAppChannel: body.Channel,
		},
	}
	if body.Username != "" {
		secretMeta.Annotations[constant.AnnotationAppCreator] = body.Username
	}
	err = completeAppLabels(m.ctx, rel, m.ks, logger, secretMeta)
	analysis.Install(body.Chart, rel.Chart.Metadata.Version).WithFeatures(feats).Success()
	return err
}

func writeValuesFile(data map[string]interface{}) (string, error) {
	f, err := ioutil.TempFile("/tmp", "values.******.yaml")
	if err != nil {
		return "", err
	}
	vars, err := yaml.Marshal(data)
	if err != nil {
		return "nil", err
	}
	_, err = f.Write(vars)
	if err != nil {
		return "nil", err
	}
	_ = f.Close()
	return f.Name(), nil
}

func parseFeatures(values map[string]interface{}) []string {
	feats := make([]string, 0)

	// parse global database
	if kvpath.Exist(values, "mysql.enabled") {
		if !kvpath.ReadBool(values, "mysql.enabled") && kvpath.Exist(values, "mysql.auth.dbservice") {
			feats = append(feats, "gdb")
		}
	} else if kvpath.Exist(values, "postgresql.enabled") {
		if !kvpath.ReadBool(values, "postgresql.enabled") && kvpath.Exist(values, "postgresql.auth.dbservice") {
			feats = append(feats, "gdb")
		}
	} else if kvpath.Exist(values, "mongodb.enabled") {
		if !kvpath.ReadBool(values, "mongodb.enabled") && kvpath.Exist(values, "mongodb.auth.dbservice") {
			feats = append(feats, "gdb")
		}
	}

	return feats
}
