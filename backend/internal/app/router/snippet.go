// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"fmt"
	"net/http"
	"strings"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/snippet"
)

func ListSnippets(c *gin.Context) {
	var (
		err error
		ctx = c.Request.Context()
		op  model.QueryNamespace
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindQuery(&op); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	data, err := service.Snippets(ctx, op.Cluster).List(op.Namespace)
	if err != nil {
		logger.WithError(err).Error("failed to list snippets")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func CreateSnippet(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.SnippetConfig
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindJSON(&body); err != nil {
		logger.WithError(err).Error("bind json failed")
		renderError(c, http.StatusOK, err)
		return
	}
	logger.Debugf("receive snippet create request: %+v", body)

	if !strings.HasPrefix(body.Name, snippet.NamePrefix) {
		e := fmt.Errorf("snippet name should start with 'snippet-'")
		logger.WithError(err).Error("invalid post data")
		renderError(c, http.StatusBadRequest, e)
		return
	}

	err = service.Snippets(ctx, body.Cluster).Create(body.Name, body.Namespace, body.Category, body.Values, body.AutoImport)
	if err != nil {
		logger.WithError(err).Error("failed to create snippet")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderSuccess(c, http.StatusOK)
}

func ReadSnippet(c *gin.Context) {
	var (
		ctx = c.Request.Context()
		err error
		req model.SnippetQuery
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindQuery(&req); err != nil {
		logger.WithError(err).Error("bind json failed")
		renderError(c, http.StatusOK, err)
		return
	}

	obj, err := service.Snippets(ctx, req.Cluster).Get(req.Name, req.Namespace)
	if err != nil {
		logger.WithError(err).Error("failed to create snippet")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, obj.Values())
}

func UpdateSnippet(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.SnippetConfig
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindJSON(&body); err != nil {
		logger.WithError(err).Error("bind json failed")
		renderError(c, http.StatusOK, err)
		return
	}
	logger.Debugf("receive snippet update request: %+v", body)

	err = service.Snippets(ctx, body.Cluster).Update(body.Name, body.Namespace, body.Category, body.Values, body.AutoImport)
	if err != nil {
		logger.WithError(err).Error("failed to create snippet")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderSuccess(c, http.StatusOK)
}

func RemoveSnippet(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.ResourceModel
	)

	logger := getLogger(ctx)
	if err = c.ShouldBindJSON(&body); err != nil {
		logger.WithError(err).Error("bind json failed")
		renderError(c, http.StatusOK, err)
		return
	}

	if service.Snippets(ctx, body.Cluster).Has(body.Name, body.Namespace) {
		err = service.Snippets(ctx, body.Cluster).Remove(body.Name, body.Namespace)
		if err != nil {
			logger.WithError(err).Error("failed to remove snippet")
			renderError(c, http.StatusInternalServerError, err)
			return
		}
	}

	renderSuccess(c, http.StatusOK)
}
