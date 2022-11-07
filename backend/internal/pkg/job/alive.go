// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package job

import (
	"context"
	"fmt"
	"os"
	"time"

	"github.com/ergoapi/util/environ"
	"github.com/imroc/req/v3"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

type alive struct {
	Domain   string `json:"domain"`
	KID      string `json:"kid,omitempty"`
	QVersion string `json:"qversion"`
	Channel  string `json:"channel"`
}

func CheckOSSAlive() error {
	logger := logging.DefaultLogger().WithField("job", "checkossalive")
	t := time.Now()
	kid := ""
	kubeCluster := cluster.Get("primary")
	kubens, _ := kubeCluster.Store.Clients.Base.CoreV1().Namespaces().Get(context.Background(), "kube-system", metav1.GetOptions{})
	if kubens != nil {
		kid = string(kubens.GetUID())
	}
	aliveBody := alive{
		Domain:   os.Getenv("APP_DOMAIN"),
		QVersion: os.Getenv("APP_VERSION"),
		Channel:  os.Getenv("CLOUD_DEFAULT_CHANNEL"),
		KID:      kid,
	}
	logger.Infof("start send alive domain %s", aliveBody.Domain)
	client := req.C()
	resp, err := client.R().
		SetHeader("accept", "application/json").
		SetHeader("oss-token", environ.GetEnv("CLOUD_API_TOKEN", "qoss-miss")).
		SetBody(&aliveBody).Post("https://api.qucheng.com/api/qoss/alive")
	if err != nil {
		return err
	}
	if !resp.IsSuccess() {
		return fmt.Errorf("bad response status: %s", resp.Status)
	}
	logger.Infof("send alive domain %s success, cost: %v", aliveBody.Domain, time.Since(t))
	return nil
}
