// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/pkg/errors"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"
)

// GDBList 全局数据库列表
// @Summary 全局数据库列表
// @Tags 全局数据库列表
// @Description 全局数据库列表
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/component/gdb [get]
func GDBList(c *gin.Context) {
	ctx := c.Request.Context()
	var op model.CompDbServiceListModel
	var err error

	if err = c.ShouldBindQuery(&op); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err := service.Components(ctx, "").ListDbsComponents(op.Kind, op.Namespace)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errListDbServiceFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errListDbServiceFailed))
		return
	}

	renderJson(c, http.StatusOK, i)
}

func GDBValidation(c *gin.Context) {
	ctx := c.Request.Context()
	var op model.CompDbServiceValidationModel
	var err error

	if err = c.ShouldBindQuery(&op); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err := service.Components(ctx, "").ValidDbService(op.Name, op.Namespace, op.Database, op.User)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errValidDbSvcFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errValidDbSvcFailed))
		return
	}

	renderJson(c, http.StatusOK, i)
}
