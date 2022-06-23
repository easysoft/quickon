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

	"github.com/gin-gonic/gin"
	"k8s.io/klog/v2"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/router"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/cron"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
)

const (
	listenPort = 8087
)

func Serve(ctx context.Context) error {
	var err error
	stopCh := make(chan struct{})

	klog.Info("Initialize clusters")
	for cluster.Init(stopCh) != nil {
		klog.Errorf("initialize failed")
		time.Sleep(time.Second * 10)
	}

	klog.Info("Setup cron tasks")
	_ = helm.RepoUpdate()
	defer cron.Cron.Stop()
	cron.Cron.Add("* * * * *", func() {
		err = helm.RepoUpdate()
		if err != nil {
			klog.Warningf("cron helm repo update err: %v", err)
		}
	})
	cron.Cron.Start()

	klog.Info("Starting cne-api...")

	klog.Info("Setup gin engine")
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
			klog.Errorf("Failed to stop server, error: %s", err)
		}
		klog.Info("server exited.")
	}()
	klog.Infof("start application server, Listen on port: %d, pid is %v", listenPort, os.Getpid())
	if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
		klog.Errorf("Failed to start http server, error: %s", err)
		return err
	}
	<-stopCh
	return nil
}
