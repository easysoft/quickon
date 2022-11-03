package patch

import (
	"context"
	"fmt"
	"os"

	"github.com/Masterminds/semver"
	"github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

func init() {
	register(patchOperatorEnableMinioIngress)
}

/*
patchOperatorEnableMinioIngress will upgrade cne-operator,
enable minio's ingress and set ingress host,
while cne-operator's version is less than 2.3.0
todo
remove this patch after version 2.5.0
*/
func patchOperatorEnableMinioIngress(ctx context.Context) error {
	logger := logging.DefaultLogger().WithFields(logrus.Fields{"action": "patch", "patchName": "enable minio ingress"})
	runtimeNs := viper.GetString(constant.FlagRuntimeNamespace)

	opApp, err := service.Apps(ctx, "", runtimeNs).GetApp("cne-operator")
	if err != nil {
		return err
	}

	// compare current version and expect version
	opVersion := opApp.GetRelease().Chart.Metadata.Version
	chartVersion, _ := semver.NewVersion(opVersion)
	expectVersion, _ := semver.NewVersion("2.3.0")

	if chartVersion.Compare(expectVersion) >= 0 {
		logger.Infof("cne-operator version is valid, skip")
	}

	// read app domain from env, only support in pod
	domain, ok := os.LookupEnv(constant.ENV_APP_DOMAIN)
	if !ok {
		err = fmt.Errorf("env %s not found", constant.ENV_APP_DOMAIN)
		logger.WithError(err).Error("parse domain failed")
		return err
	}

	// prepare upgrade settings
	logger.Info("start patch to enable minio ingress")
	minioHost := "s3." + domain
	settings := []model.StringSetting{
		{"minio.ingress.enabled", "true"},
		{"minio.ingress.host", minioHost},
	}

	// do upgrade
	logger.Info("upgrade cne-operator")
	channel := "test"
	if c, ok := os.LookupEnv(constant.ENV_DEFAULT_CHANNEL); ok {
		channel = c
	}
	err = opApp.PatchSettings(opApp.ChartName, model.AppCreateOrUpdateModel{
		Version: "latest", Channel: channel, Settings: settings,
	}, nil, nil)

	return err
}
