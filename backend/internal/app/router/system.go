package router

import (
	"github.com/gin-gonic/gin"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"
	"net/http"
)

func SystemUpdate(c *gin.Context) {
	var (
		err  error
		ctx  = c.Request.Context()
		body model.ReqSystemUpdate
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	qcApp, err := service.Apps(ctx, "", constant.DEFAULT_RUNTIME_NAMESPACE).GetApp("qucheng")
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = qcApp.PatchSettings(qcApp.ChartName, model.AppCreateOrUpdateModel{
		Version: body.Version, Channel: body.Channel,
	}); err != nil {
		tlog.WithCtx(ctx).InfoS("update qucheng chart failed", "version", body.Version, "channel", body.Channel)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	tlog.WithCtx(ctx).InfoS("update qucheng chart success", "version", body.Version, "channel", body.Channel)

	opApp, err := service.Apps(ctx, "", constant.DEFAULT_RUNTIME_NAMESPACE).GetApp("cne-operator")
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = opApp.PatchSettings(opApp.ChartName, model.AppCreateOrUpdateModel{
		Version: "latest", Channel: body.Channel,
	}); err != nil {
		tlog.WithCtx(ctx).InfoS("update operator chart failed", "channel", body.Channel)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	tlog.WithCtx(ctx).InfoS("update operator chart success", "channel", body.Channel)
	renderSuccess(c, http.StatusOK)
}
