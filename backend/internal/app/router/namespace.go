// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/pkg/errors"
	"k8s.io/klog/v2"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
)

// NamespaceCreate 创建命名空间
// @Summary 创建命名空间
// @Tags 命名空间
// @Description 创建命名空间
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.NamespaceBase true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/namespace/create [post]
func NamespaceCreate(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.NamespaceCreate
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if err = service.Namespaces(ctx, body.Cluster).Create(body.Name); err != nil {
		klog.ErrorS(err, "create namespace failed", "name", body.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	klog.InfoS("create namespace successful", "name", body.Name)
	renderSuccess(c, http.StatusOK)
}

// NamespaceRecycle 删除命名空间
// @Summary 删除命名空间
// @Tags 命名空间
// @Description 删除命名空间
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.NamespaceBase true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/namespace/recycle [post]
func NamespaceRecycle(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.NamespaceBase
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if err = service.Namespaces(ctx, body.Cluster).Recycle(body.Name); err != nil {
		klog.ErrorS(err, "recycle namespace failed", "name", body.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	klog.InfoS("recycle namespace successful", "name", body.Name)
	renderSuccess(c, http.StatusOK)
}

// NamespaceGet 查询命名空间
// @Summary 查询命名空间
// @Tags 命名空间
// @Description 查询命名空间
// @Accept multipart/form-data
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body query model.NamespaceBase true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/namespace [get]
func NamespaceGet(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.NamespaceBase
	)

	if err = c.ShouldBindQuery(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if ok := service.Namespaces(ctx, body.Cluster).Has(body.Name); !ok {
		renderError(c, http.StatusNotFound, errors.New("namespace not found"))
		return
	}

	renderSuccess(c, http.StatusOK)
}
