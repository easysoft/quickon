// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"fmt"
	"net/http"
	"time"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/validator"

	"github.com/gin-gonic/gin"
	"github.com/prometheus/client_golang/prometheus/promhttp"
	ginSwagger "github.com/swaggo/gin-swagger"
	"github.com/swaggo/gin-swagger/swaggerFiles"

	_ "gitlab.zcorp.cc/pangu/cne-api/docs"
)

func Config(r *gin.Engine) {
	validator.Setup()

	r.Use(Cors())
	r.Use(gin.LoggerWithConfig(gin.LoggerConfig{
		SkipPaths: []string{"/health", "/metrics"},
		Formatter: func(param gin.LogFormatterParams) string {
			return fmt.Sprintf(`time="%s" client=%s method=%s path=%s proto=%s status=%d cost=%s user-agent="%s" error="%s" traceId=%s`+"\n",
				param.TimeStamp.Format(time.RFC3339),
				param.ClientIP,
				param.Method,
				param.Path,
				param.Request.Proto,
				param.StatusCode,
				param.Latency.String(),
				param.Request.UserAgent(),
				param.ErrorMessage,
				param.Request.Header.Get(HeaderTraceId),
			)
		},
	}))
	r.Use(gin.Recovery())
	r.GET("/ping", ping)
	r.GET("/health", health)
	r.GET("/metrics", gin.WrapH(promhttp.Handler()))
	r.GET("/docs/*any", ginSwagger.WrapHandler(swaggerFiles.Handler))
	api := r.Group("/api/cne", Auth(), Trace())
	{
		api.POST("/app/install", AppInstall)
		api.POST("/app/uninstall", AppUnInstall)
		api.POST("/app/start", AppStart)
		api.POST("/app/stop", AppStop)
		api.POST("/app/restart", AppRestart)
		api.POST("/app/suspend", AppSuspend)
		api.POST("/app/settings", AppPatchSettings)
		api.GET("/app/settings/simple", AppSimpleSettings)
		api.GET("/app/settings/common", AppCommonSettings)
		api.GET("/app/settings/custom", AppCustomSettings)
		api.GET("/app/status", AppStatus)
		api.POST("/app/status/multi", AppListStatus)
		api.GET("/app/domain", AppDomain)
		api.GET("/app/metric", AppMetric)
		api.GET("/app/pvc", AppPvcList)
		api.GET("/app/account", AppAccountInfo)
		api.GET("/app/dbs", AppDbList)
		api.GET("/app/dbs/detail", AppDbDetails)

		api.GET("/test", AppTest)

		api.GET("/app/components", AppComponents)
		//api.GET("/app/component/categories", AppComCategory)
		//api.GET("/app/component/schema", AppComSchema)

		api.POST("/app/backup", AppBackupCreate)
		api.GET("/app/backups", AppBackupList)
		api.GET("/app/backup/status", AppBackupStatus)
		api.GET("/app/backup/detail")
		api.POST("/app/backup/remove", AppBackupRemove)

		api.POST("/app/restore", AppRestoreCreate)
		api.GET("/app/restore")
		api.GET("/app/restore/status", AppRestoreStatus)
		api.POST("/app/restore/remove", AppRestoreRemove)

		api.GET("/component/gdb", GDBList)
		api.GET("/component/gdb/validation", GDBValidation)

		api.GET("/component/dbservice", DbServiceList)
		api.GET("/component/dbservice/detail", DbServiceDetail)

		api.POST("/namespace/create", NamespaceCreate)
		api.POST("/namespace/recycle", NamespaceRecycle)
		api.GET("/namespace", NamespaceGet)

		api.POST("/middleware/install", MiddlewareInstall)
		api.POST("/middleware/uninstall", MiddleWareUninstall)

		api.POST("/statistics/app", AppListStatistics)
		api.GET("/statistics/cluster", ClusterStatistics)

		api.POST("/system/update", SystemUpdate)
		api.GET("/system/app-full-list", FindAllApps)
		api.POST("/system/smtp/validator", AuthMailServer)
		api.POST("/system/tls/upload", UploadTLS)
		api.GET("/system/tls/info", ReadTLSInfo)
		api.GET("/system/qlb/config", GetLoadBalancer)
		api.POST("/system/qlb/config", ConfigLoadBalancer)

		api.GET("/snippet", ListSnippets)
		api.GET("/snippet/read", ReadSnippet)
		api.POST("/snippet/add", CreateSnippet)
		api.POST("/snippet/update", UpdateSnippet)
		api.POST("/snippet/remove", RemoveSnippet)
	}

	r.NoMethod(func(c *gin.Context) {
		msg := fmt.Sprintf("not found: %v", c.Request.Method)
		renderMessage(c, http.StatusBadRequest, msg)
	})
	r.NoRoute(func(c *gin.Context) {
		msg := fmt.Sprintf("not found: %v", c.Request.URL.Path)
		renderMessage(c, http.StatusBadRequest, msg)
	})
}
