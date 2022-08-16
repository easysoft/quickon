package router

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/snippet"
	"net/http"
	"strings"
)

func ListSnippets(c *gin.Context) {
	var (
		err error
		ctx = c.Request.Context()
	)

	logger := getLogger(ctx)
	namespace := c.DefaultQuery("namespace", "")

	data, err := service.Snippets(ctx, "").List(namespace)
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

	err = service.Snippets(ctx, "").Create(body.Name, body.Namespace, body.Content)
	if err != nil {
		logger.WithError(err).Error("failed to create snippet")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderSuccess(c, http.StatusOK)
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

	err = service.Snippets(ctx, "").Update(body.Name, body.Namespace, body.Content)
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

	err = service.Snippets(ctx, body.Cluster).Remove(body.Name, body.Namespace)
	if err != nil {
		logger.WithError(err).Error("failed to remove snippet")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderSuccess(c, http.StatusOK)
}
