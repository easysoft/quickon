// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"fmt"
	"net/http"
	"sync"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"

	"github.com/gin-gonic/gin"
	"github.com/pkg/errors"
)

// AppInstall 安装接口
// @Summary 安装接口
// @Tags 应用管理
// @Description 安装接口
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.AppCreateOrUpdateModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/install [post]
func AppInstall(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.AppCreateOrUpdateModel
		i    *app.Instance
	)
	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if i != nil {
		tlog.WithCtx(ctx).ErrorS(nil, "app already exists, install can't continue",
			"cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name, "chart", body.Chart)
		renderError(c, http.StatusInternalServerError, errors.New("app already installed"))
		return
	}

	if err = service.Apps(ctx, body.Cluster, body.Namespace).Install(body.Name, body); err != nil {
		tlog.WithCtx(ctx).ErrorS(err, "install app failed",
			"cluster", body.Cluster, "namespace", body.Namespace,
			"name", body.Name, "chart", body.Chart)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	tlog.WithCtx(ctx).InfoS("install app successful",
		"cluster", body.Cluster, "namespace", body.Namespace,
		"name", body.Name, "chart", body.Chart)
	renderSuccess(c, http.StatusCreated)
}

// AppUnInstall 卸载接口
// @Summary 卸载接口
// @Tags 应用管理
// @Description 卸载接口
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.AppModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/uninstall [post]
func AppUnInstall(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.AppModel
	)
	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	_, err = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = service.Apps(ctx, body.Cluster, body.Namespace).UnInstall(body.Name); err != nil {
		tlog.WithCtx(ctx).ErrorS(err, "uninstall app failed",
			"cluster", body.Cluster, "namespace", body.Namespace,
			"app", body.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	tlog.WithCtx(ctx).InfoS("uninstall app successful",
		"cluster", body.Cluster, "namespace", body.Namespace,
		"app", body.Name)
	renderSuccess(c, http.StatusOK)
}

// AppStart 启动应用
// @Summary 启动应用
// @Tags 应用管理
// @Description 启动应用
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.AppManageModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/start [post]
func AppStart(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.AppManageModel

		i *app.Instance
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}
	i, err = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errStartAppFailed))
		return
	}

	err = i.Start(body.Chart, body.Channel)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errStartAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		renderError(c, http.StatusInternalServerError, errors.New(errStartAppFailed))
		return
	}
	tlog.WithCtx(ctx).InfoS("start app successful", "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
	renderSuccess(c, http.StatusOK)
}

// AppStop 关闭应用
// @Summary 关闭应用
// @Tags 应用管理
// @Description 关闭应用
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.AppManageModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/stop [post]
func AppStop(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.AppManageModel

		i *app.Instance
	)
	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errStopAppFailed))
		return
	}

	err = i.Stop(body.Chart, body.Channel)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errStopAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		renderError(c, http.StatusInternalServerError, errors.New(errStopAppFailed))
		return
	}
	tlog.WithCtx(ctx).InfoS("stop app successful", "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
	renderSuccess(c, http.StatusOK)
}

// AppStop 设置应用
// @Summary 设置应用
// @Tags 应用管理
// @Description 设置应用
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body body model.AppCreateOrUpdateModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/settings [post]
func AppPatchSettings(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body model.AppCreateOrUpdateModel

		i *app.Instance
	)
	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errPatchAppFailed))
		return
	}

	err = i.PatchSettings(body.Chart, body)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errPatchAppFailed, "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
		renderError(c, http.StatusInternalServerError, errors.New(errPatchAppFailed))
		return
	}
	tlog.WithCtx(ctx).InfoS("patch app settings successful", "cluster", body.Cluster, "namespace", body.Namespace, "name", body.Name)
	renderSuccess(c, http.StatusOK)
}

// AppStatus 应用状态
// @Summary 应用状态
// @Tags 应用管理
// @Description 应用状态
// @Accept json
// @Produce json
// @Param Authorization header string false "jwtToken"
// @Param X-Auth-Token header string false "staticToken"
// @Security ApiKeyAuth
// @Param body query model.AppModel true "meta"
// @Success 201 {object} response2xx
// @Failure 500 {object} response5xx
// @Router /api/cne/app/status [get]
func AppStatus(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
		data  *model.AppRespStatus
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data = i.ParseStatus()

	/*
		parse App Uri
	*/
	data.AccessHost = ""
	ingressHosts := i.ListIngressHosts()
	if len(ingressHosts) > 0 {
		data.AccessHost = ingressHosts[0]
	} else {
		nodePort := i.ParseNodePort()
		if nodePort > 0 {
			nodePortIPS := service.Nodes(ctx, query.Cluster).ListNodePortIPS()
			if len(nodePortIPS) != 0 {
				accessHost := fmt.Sprintf("%s:%d", nodePortIPS[0], nodePort)
				data.AccessHost = accessHost
			}
		}
	}

	renderJson(c, http.StatusOK, data)
}

func AppSimpleSettings(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppSettingMode
		i     *app.Instance
	)

	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	settings, err := i.Settings().Simple().Mode(query.Mode).Parse()
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, "get simple settings failed", "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	renderJson(c, http.StatusOK, settings)
}

func AppMetric(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data := i.GetMetrics()
	renderJson(c, http.StatusOK, data)
}

func AppListStatistics(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		wg   sync.WaitGroup
		err  error
		body model.AppListModel
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	metricList := make([]model.NamespaceAppMetric, len(body.Apps))

	for id, a := range body.Apps {
		wg.Add(1)
		go func(index int, na model.NamespacedApp) {
			defer wg.Done()
			tlog.WithCtx(ctx).InfoS("test", "index", index, "namespace", na.Namespace, "name", na.Name)

			metricList[index].Name = na.Name
			metricList[index].Namespace = na.Namespace

			i, err := service.Apps(ctx, body.Cluster, na.Namespace).GetApp(na.Name)
			if err != nil {
				tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "namespace", na.Namespace, "name", na.Name)
				return
			}
			m := i.GetMetrics()
			metricList[index].Metrics = m

			s := i.ParseStatus()
			metricList[index].Status = s.Status
			metricList[index].Age = s.Age
		}(id, a)
	}

	wg.Wait()
	renderJson(c, http.StatusOK, metricList)
}

func AppComponents(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data := i.GetComponents()
	renderJson(c, http.StatusOK, data)
}

func AppComCategory(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppComponentModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data := i.GetSchemaCategories(query.Component)
	renderJson(c, http.StatusOK, data)
}

func AppComSchema(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppSchemaModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data := i.GetSchema(query.Component, query.Category)
	renderJson(c, http.StatusOK, data)
}

func AppPvcList(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data := i.GetPvcList()
	renderJson(c, http.StatusOK, data)
}

func AppTest(c *gin.Context) {

	ch, err := helm.GetChart("qucheng-test/demo", "0.1.1")
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	for _, dep := range ch.Dependencies() {
		fmt.Println(dep.Name(), dep.Files)
	}
	renderSuccess(c, 200)
}
