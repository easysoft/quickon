// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package serve

import (
	"context"
	"fmt"
	"net/http"
	"os"
	"time"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/patch"

	"github.com/spf13/viper"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/job"

	"github.com/sirupsen/logrus"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/router"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/cron"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

const (
	listenPort = 8087
)

func Serve(ctx context.Context, logger logrus.FieldLogger) error {
	var err error
	stopCh := make(chan struct{})

	logger.Infof("Setup runtime namespace to %s", viper.GetString(constant.FlagRuntimeNamespace))

	logger.Info("Initialize clusters")
	for cluster.Init(stopCh) != nil {
		logger.Errorf("initialize kubernetes client failed")
		time.Sleep(time.Second * 10)
	}

	logger.Info("Setup analysis")
	als := analysis.Init()
	go als.Run(ctx)

	logger.Info("Setup cron tasks")
	_ = helm.RepoUpdate()
	go job.LoadJob()

	cr := cron.New()
	defer cr.Stop()
	cr.Add("01 * * * *", func() {
		err = helm.RepoUpdate()
		if err != nil {
			logger.WithError(err).Warning("cron helm repo update failed")
		}
	})
	cr.Add("@midnight", func() {
		job.LoadJob()
	})
	cr.Start()

	// apply patches
	_ = patch.Run(ctx)

	logger.Info("Starting cne-api...")

	logger.Info("Setup gin engine")
	r := gin.New()
	router.Config(r)

	srv := &http.Server{
		Addr:    fmt.Sprintf(":%d", listenPort),
		Handler: r,
	}
	go func() {
		defer close(stopCh)
		<-ctx.Done()
		ctx, cancel := context.WithTimeout(context.TODO(), time.Second*5)
		defer cancel()
		if err := srv.Shutdown(ctx); err != nil {
			logger.WithError(err).Error("Failed to stop server")
		}
		logger.Info("server exited.")
	}()
	logger.Infof("start application server, Listen on port: %d, pid is %v", listenPort, os.Getpid())
	if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
		logger.WithError(err).Error("Failed to start http server")
		return err
	}
	<-stopCh
	return nil
}
