// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"fmt"
	"net/http"
	"sync"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app/instance"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/db/manage"

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"

	"github.com/gin-gonic/gin"
	"github.com/pkg/errors"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
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
		i    *instance.Instance
	)
	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	logger := getLogger(ctx).WithFields(logrus.Fields{
		"name": body.Name, "namespace": body.Namespace,
	})

	logger.Infof("start app install with chart %s in channel %s, version %s", body.Chart, body.Channel, body.Version)

	i, _ = service.Apps(ctx, body.Cluster, body.Namespace).GetApp(body.Name)
	if i != nil {
		logger.WithError(err).Error("app already exists, install can't continue")
		renderError(c, http.StatusInternalServerError, errors.New("app already installed"))
		return
	}

	snippetSettings, _ := MergeSnippetConfigs(ctx, body.Namespace, body.SettingsSnippets, logger)

	if err = service.Apps(ctx, body.Cluster, body.Namespace).Install(body.Name, body, snippetSettings); err != nil {
		logger.WithError(err).Error("install app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	logger.Info("install app successful")
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
		body model.AppModel
	)

	_, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	if err = i.Uninstall(); err != nil {
		logger.WithError(err).Error("uninstall app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	logger.Info("uninstall app successful")
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
		body model.AppManageModel
	)

	ctx, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	// start app with auto-import snippet settings
	snippetSettings, _ := MergeSnippetConfigs(ctx, body.Namespace, []string{}, logger)
	err = i.Start(body.Chart, body.Channel, snippetSettings)
	if err != nil {
		logger.WithError(err).Error(errStartAppFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errStartAppFailed))
		return
	}
	logger.Info("start app successful")
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
		body model.AppManageModel
	)

	_, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	err = i.Stop(body.Chart, body.Channel)
	if err != nil {
		logger.WithError(err).Error(errStopAppFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errStopAppFailed))
		return
	}
	logger.Info("stop app successful")
	renderSuccess(c, http.StatusOK)
}

func AppSuspend(c *gin.Context) {
	var (
		body model.AppManageModel
	)

	_, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	err = i.Suspend(body.Chart, body.Channel)
	if err != nil {
		logger.WithError(err).Error(errStopAppFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errStopAppFailed))
		return
	}
	logger.Info("suspend app successful")
	renderSuccess(c, http.StatusOK)
}

func AppRestart(c *gin.Context) {
	var (
		body model.AppManageModel
	)

	_, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	err = i.Restart(body.Chart, body.Channel)
	if err != nil {
		logger.WithError(err).Error(errStopAppFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errStopAppFailed))
		return
	}
	logger.Info("restart app successful")
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
		body model.AppCreateOrUpdateModel
	)

	ctx, i, code, err := LookupApp(c, &body)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	snippetSettings, delSnippetSettings := MergeSnippetConfigs(ctx, body.Namespace, body.SettingsSnippets, logger)

	err = i.PatchSettings(body.Chart, body, snippetSettings, delSnippetSettings)
	if err != nil {
		logger.WithError(err).Error(errPatchAppFailed)
		renderError(c, http.StatusInternalServerError, errors.New(errPatchAppFailed))
		return
	}
	logger.Info("patch app settings successful")
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
		query model.AppModel
	)

	ctx, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()
	logger.Debug("start parse status")
	data := i.ParseStatus()
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

func AppDomain(c *gin.Context) {
	var (
		query model.AppWithComponentModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	data, err := i.GetDomains(query.Component)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AppListStatus(c *gin.Context) {
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

	logger := getLogger(ctx)
	statusList := make([]model.NamespacedAppRespStatus, len(body.Apps))

	for id, a := range body.Apps {
		wg.Add(1)
		go func(index int, na model.NamespacedApp) {
			defer wg.Done()

			statusList[index].Namespace = na.Namespace
			statusList[index].Name = na.Name

			i, e := service.Apps(ctx, body.Cluster, na.Namespace).GetApp(na.Name)
			if e != nil {
				logger.WithError(e).WithFields(logrus.Fields{"name": na.Name, "namespace": na.Namespace}).Error(errGetAppFailed)
				return
			}

			status := i.ParseStatus()
			statusList[index].Status = status.Status
			statusList[index].Age = status.Age
			statusList[index].Version = status.Version

			ingressHosts := i.ListIngressHosts()
			if len(ingressHosts) > 0 {
				statusList[index].AccessHost = ingressHosts[0]
			} else {
				nodePort := i.ParseNodePort()
				if nodePort > 0 {
					nodePortIPS := service.Nodes(ctx, body.Cluster).ListNodePortIPS()
					if len(nodePortIPS) != 0 {
						accessHost := fmt.Sprintf("%s:%d", nodePortIPS[0], nodePort)
						statusList[index].AccessHost = accessHost
					}
				}
			}

		}(id, a)
	}
	wg.Wait()

	renderJson(c, http.StatusOK, statusList)
}

func AppSimpleSettings(c *gin.Context) {
	var (
		query model.AppSettingMode
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()

	settings, err := i.Settings().Simple().Mode(query.Mode).Parse()
	if err != nil {
		logger.WithError(err).Error("get simple settings failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	renderJson(c, http.StatusOK, settings)
}

func AppCommonSettings(c *gin.Context) {
	var (
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()
	settings, err := i.Settings().Common()
	if err != nil {
		logger.WithError(err).Error("get common settings failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	renderJson(c, http.StatusOK, settings)
}

func AppCustomSettings(c *gin.Context) {
	var (
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()
	settings, err := i.Settings().Custom()
	if err != nil {
		logger.WithError(err).Error("get custom settings failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	renderJson(c, http.StatusOK, settings)
}

func AppMetric(c *gin.Context) {
	var (
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
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

	logger := getLogger(ctx)
	metricList := make([]model.NamespaceAppMetric, len(body.Apps))

	for id, a := range body.Apps {
		wg.Add(1)
		go func(index int, na model.NamespacedApp) {
			defer wg.Done()

			metricList[index].Name = na.Name
			metricList[index].Namespace = na.Namespace

			i, err := service.Apps(ctx, body.Cluster, na.Namespace).GetApp(na.Name)
			if err != nil {
				logger.WithError(err).WithFields(logrus.Fields{"name": na.Name, "namespace": na.Namespace}).Error(errGetAppFailed)
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
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	data := i.GetComponents()
	renderJson(c, http.StatusOK, data)
}

//func AppComCategory(c *gin.Context) {
//	var (
//		query model.AppComponentModel
//	)
//
//	_, i, code, err := LookupApp(c, &query)
//	if err != nil {
//		renderError(c, code, err)
//		return
//	}
//
//	data := i.GetSchemaCategories(query.Component)
//	renderJson(c, http.StatusOK, data)
//}
//
//func AppComSchema(c *gin.Context) {
//	var (
//		query model.AppSchemaModel
//	)
//
//	_, i, code, err := LookupApp(c, &query)
//	if err != nil {
//		renderError(c, code, err)
//		return
//	}
//
//	data := i.GetSchema(query.Component, query.Category)
//	renderJson(c, http.StatusOK, data)
//}

func AppPvcList(c *gin.Context) {
	var (
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	data := i.GetPvcList()
	renderJson(c, http.StatusOK, data)
}

func AppAccountInfo(c *gin.Context) {
	var (
		query model.AppWithComponentModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	data := i.GetAccountInfo(query.Component)
	renderJson(c, http.StatusOK, data)
}

func AppDbList(c *gin.Context) {
	var (
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()
	dbs := i.GetDbList()
	var data []model.ComponentDb
	for _, item := range dbs {
		dbsvc, dbMeta, err := manage.ParseDB(i.Ctx, i.Ks.Store, item)
		if err != nil {
			logger.WithError(err).Errorf("parse db %s failed", item.Name)
			continue
		}
		ready := false
		if item.Status.Ready != nil {
			ready = *item.Status.Ready
		}
		d := model.ComponentDb{
			ComponentBase: model.ComponentBase{Name: item.Name, NameSpace: item.Namespace},
			DbType:        string(dbsvc.DbType()),
			DbName:        dbMeta.Name,
			Ready:         ready,
		}
		data = append(data, d)
	}
	renderJson(c, http.StatusOK, data)
}

func AppDbDetails(c *gin.Context) {
	var (
		query struct {
			model.AppModel
			Db string `form:"db" binding:"required"`
		}
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	logger := i.GetLogger()
	db, err := i.Ks.Store.GetDb(query.Namespace, query.Db)
	if err != nil {
		logger.WithError(err).Error("get db failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	dbsvc, dbMeta, err := manage.ParseDB(i.Ctx, i.Ks.Store, db)
	if err != nil {
		logger.WithError(err).Errorf("parse db %s failed", query.Db)
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	data := model.ComponentDbServiceDetail{
		ComponentBase: model.ComponentBase{Name: db.Name, NameSpace: db.Namespace},
		Host:          dbsvc.ServerInfo().Host(),
		Port:          dbsvc.ServerInfo().Port(),
		UserName:      dbMeta.User,
		Password:      dbMeta.Password,
		Database:      dbMeta.Name,
	}
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
