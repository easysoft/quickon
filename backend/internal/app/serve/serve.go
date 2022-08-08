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

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"

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

	logger.Info("Initialize clusters")
	for cluster.Init(stopCh) != nil {
		logger.Errorf("initialize failed")
		time.Sleep(time.Second * 10)
	}

	logger.Info("Setup cron tasks")
	_ = helm.RepoUpdate()
	defer cron.Cron.Stop()
	cron.Cron.Add("01 * * * *", func() {
		err = helm.RepoUpdate()
		if err != nil {
			logger.WithError(err).Warning("cron helm repo update failed")
		}
	})
	cron.Cron.Start()

	service.Apps(ctx, "", "").Upgrade()

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
