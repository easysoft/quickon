// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"k8s.io/klog/v2"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
)

// MiddlewareInstall 安装中间件
// @Summary 安装中间件
// @Tags 中间件
// @Description 安装中间件
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.Middleware true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/middleware/install [post]
func MiddlewareInstall(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.Middleware
		res  interface{}
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if res, err = service.Middlewares(ctx).Mysql().CreateDB(&body); err != nil {
		klog.ErrorS(err, "create mysql db failed", "name", body.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	klog.InfoS("create mysql db successful", "name", body.Name)
	renderJson(c, http.StatusOK, res)
}

// MiddleWareUninstall 卸载中间件
// @Summary 卸载中间件
// @Tags 中间件
// @Description 卸载中间件
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.Middleware true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/middleware/uninstall [post]
func MiddleWareUninstall(c *gin.Context) {

	var (
		ctx  = c.Request.Context()
		err  error
		body model.Middleware
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if err = service.Middlewares(ctx).Mysql().RecycleDB(&body); err != nil {
		klog.ErrorS(err, "recycle mysql db failed", "name", body.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	klog.InfoS("recycle mysql db successful", "name", body.Name)
	renderSuccess(c, http.StatusOK)
}
