// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package patch

import (
	"context"
	"errors"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app/instance"
	"k8s.io/apimachinery/pkg/labels"
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
	register(patchUpgradeCneCourierForTLS)
}

/*
patchUpgradeCneCourierForTLS ensure the selfcert chart is installed for ca certifications,
enable minio's ingress and set ingress host,
update cne-courier release version greater than 2.6.0
todo
remove this patch after version 2.8.0
*/
func patchUpgradeCneCourierForTLS(ctx context.Context) error {
	// prepare
	logger := logging.DefaultLogger().WithFields(logrus.Fields{"action": "patch", "patchName": "update cne-courier for tls support"})
	runtimeNs := viper.GetString(constant.FlagRuntimeNamespace)
	channel := "test"
	if c, ok := os.LookupEnv(constant.ENV_DEFAULT_CHANNEL); ok {
		channel = c
	}

	// ensure selfcert is installed
	const scName = "selfcert"
	scApp, err := service.Apps(ctx, "", runtimeNs).GetApp(scName)
	if err != nil {
		if errors.Is(err, instance.ErrAppNotFound) {
			err = service.Apps(ctx, "", runtimeNs).Install(scName, model.AppCreateOrUpdateModel{
				Channel:  channel,
				Chart:    scName,
				Version:  "",
				Username: "patcher",
			}, nil)
			if err != nil {
				logger.WithError(err).Errorf("system app %s install failed", scName)
				return err
			}
			logger.Infof("system app %s is installed", scName)
			scApp, err = service.Apps(ctx, "", runtimeNs).GetApp(scName)
		} else {
			logger.WithError(err).Errorf("load app %s failed", scName)
			return err
		}
	}
	logger.Infof("system app %s is already installed, skip.", scName)

	// find cne-courier release name
	ks := scApp.Ks
	deploys, err := ks.Store.ListDeployments(runtimeNs, labels.Set{constant.LabelApp: "cne-courier"}.AsSelector())
	if err != nil {
		return err
	}

	// cne-courier is not installed, skip.
	if len(deploys) == 0 {
		logger.Info("cne-courier is not installed, skip")
		return nil
	}

	// load app with deploy name
	courierName := deploys[0].Name
	courierApp, err := service.Apps(ctx, "", runtimeNs).GetApp(courierName)
	if err != nil {
		logger.WithError(err).Errorf("get app %s failed", courierName)
		return err
	}
	logger.Infof("found system app %s", courierName)

	// compare current version and expect version
	courierVersion := courierApp.GetRelease().Chart.Metadata.Version
	chartVersion, _ := semver.NewVersion(courierVersion)
	expectVersion, _ := semver.NewVersion("2.6.0")

	if chartVersion.Compare(expectVersion) >= 0 {
		logger.Infof("cne-courier version is valid, skip")
		return nil
	}

	// do upgrade
	logger.Info("upgrade cne-courier")

	err = courierApp.PatchSettings(courierApp.ChartName, model.AppCreateOrUpdateModel{
		Version: ">=2.6.0", Channel: channel,
	}, nil, nil)
	if err != nil {
		logger.WithError(err).Errorf("upgrade cne-courier failed")
	}
	return err
}
