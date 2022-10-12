package router

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
)

func SystemUpdate(c *gin.Context) {
	var (
		err  error
		ctx  = c.Request.Context()
		body model.ReqSystemUpdate
	)

	logger := getLogger(ctx).WithField("action", "system-update")
	if err = c.ShouldBindJSON(&body); err != nil {
		logger.WithError(err).Error(errBindDataFailed)
		renderError(c, http.StatusBadRequest, err)
		return
	}

	blankSnippet := make(map[string]interface{})
	runtimeNs := viper.GetString(constant.FlagRuntimeNamespace)

	opApp, err := service.Apps(ctx, "", runtimeNs).GetApp("cne-operator")
	if err != nil {
		logger.WithError(err).Error("get cne-operator app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = opApp.PatchSettings(opApp.ChartName, model.AppCreateOrUpdateModel{
		Version: "latest", Channel: body.Channel,
	}, blankSnippet, nil); err != nil {
		logger.WithError(err).WithField("channel", body.Channel).Info("update operator chart failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	logger.WithField("channel", body.Channel).Info("update operator chart success")

	qcApp, err := service.Apps(ctx, "", runtimeNs).GetApp("qucheng")
	if err != nil {
		logger.WithError(err).Error("get qucheng app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = qcApp.PatchSettings(qcApp.ChartName, model.AppCreateOrUpdateModel{
		Version: body.Version, Channel: body.Channel,
	}, blankSnippet, nil); err != nil {
		logger.WithError(err).WithField("channel", body.Channel).Errorf("update qucheng chart to version %s failed", body.Version)
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	logger.WithField("channel", body.Channel).Infof("update qucheng chart to version %s success", body.Version)

	renderSuccess(c, http.StatusOK)
}

func FindAllApps(c *gin.Context) {
	var (
		err error
		ctx = c.Request.Context()
	)

	logger := getLogger(ctx)

	data, err := service.Apps(ctx, "", "").ListAllApplications()
	if err != nil {
		logger.WithError(err).Error("list all application failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}
