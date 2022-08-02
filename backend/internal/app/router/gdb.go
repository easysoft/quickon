// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"net/http"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"

	"github.com/gin-gonic/gin"
	"github.com/pkg/errors"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
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
	var (
		ctx = c.Request.Context()
		err error
		op  model.CompDbServiceListModel
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindQuery(&op); err != nil {
		logger.WithError(err).Error(errBindDataFailed)
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err := service.Components(ctx, "").ListDbsComponents(op.Kind, op.Namespace)
	if err != nil {
		logger.WithError(err).Error(errListDbServiceFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errListDbServiceFailed))
		return
	}

	renderJson(c, http.StatusOK, i)
}

func GDBValidation(c *gin.Context) {

	var (
		ctx = c.Request.Context()
		op  model.CompDbServiceValidationModel
		err error
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindQuery(&op); err != nil {
		logger.WithError(err).Error(errBindDataFailed)
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err := service.Components(ctx, "").ValidDbService(op.Name, op.Namespace, op.Database, op.User)
	if err != nil {
		logger.WithError(err).Error(errValidDbSvcFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errValidDbSvcFailed))
		return
	}

	renderJson(c, http.StatusOK, i)
}
