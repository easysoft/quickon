// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package job

import (
	"context"
	"crypto/tls"
	"fmt"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/sirupsen/logrus"

	"github.com/ergoapi/util/ptr"
	"github.com/spf13/viper"
	batchv1 "k8s.io/api/batch/v1"
	corev1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

// CheckReNewCertificate 检查证书是否过期
func CheckReNewCertificate() error {
	logger := logging.DefaultLogger().WithField("job", "renewtls")
	kubeCluster := cluster.Get("primary")
	// 兼容老版本域名, 域名从ingress获取
	domain := domainGet(kubeCluster, logger)

	if strings.HasSuffix(domain, "haogs.cn") || strings.HasSuffix(domain, "corp.cc") {
		needRenew, err := checkCertificate(domain, logger)
		if err != nil {
			logger.Errorf("check domain %s tls err: %v", domain, err)
			return err
		}
		if needRenew {
			return renewCertificate(kubeCluster, domain, logger)
		}
		logger.Infof("skip domain %s renew tls", domain)
		return nil
	}
	logger.Warnf("custom domain %s skip", domain)
	return nil
}

func domainGet(kubeCluster *cluster.Cluster, logger logrus.FieldLogger) string {
	defaultDomain := os.Getenv("APP_DOMAIN")
	ingressClient := kubeCluster.Clients.Base.NetworkingV1().Ingresses(viper.GetString(constant.FlagRuntimeNamespace))
	ingress, err := ingressClient.Get(context.TODO(), "qucheng", metav1.GetOptions{})
	if err != nil {
		return defaultDomain
	}
	if ingress != nil && len(ingress.Spec.Rules) > 0 {
		return ingress.Spec.Rules[0].Host
	}
	return defaultDomain
}

func checkCertificate(domain string, logger logrus.FieldLogger) (bool, error) {
	client := &http.Client{
		Transport: &http.Transport{
			// nolint:gosec
			TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
		},
		Timeout: 10 * time.Second,
	}
	resp, err := client.Get("https://" + domain)
	if err != nil {
		return false, err
	}
	defer func() { _ = resp.Body.Close() }()
	for _, cert := range resp.TLS.PeerCertificates {
		if !cert.NotAfter.After(time.Now()) {
			logger.Warnf("domain %s tls expired", domain)
			return true, nil
		}
		// nolint:gosimple
		if cert.NotAfter.Sub(time.Now()).Hours() < 7*24 {
			logger.Warnf("domain %s tls expire after %fh", domain, cert.NotAfter.Sub(time.Now()).Hours())
			return true, nil
		}
	}
	return false, nil
}

func renewCertificate(kubeCluster *cluster.Cluster, domain string, logger logrus.FieldLogger) error {
	logging.DefaultLogger().WithField("job", "renewtls").Info("try create renewtls job")
	ds := strings.Split(domain, ".")
	coreDomain := fmt.Sprintf("%s.%s.%s", ds[len(ds)-3], ds[len(ds)-2], ds[len(ds)-1])
	// renew default tls certificate
	// renew ingress tls certificate
	jobclient := kubeCluster.Clients.Base.BatchV1().Jobs(viper.GetString(constant.FlagRuntimeNamespace))
	object := &batchv1.Job{
		TypeMeta: metav1.TypeMeta{
			Kind:       "Job",
			APIVersion: "batch/v1",
		},
		ObjectMeta: metav1.ObjectMeta{
			Name: "renewtls",
		},
		Spec: batchv1.JobSpec{
			Parallelism:  ptr.Int32Ptr(1),
			Completions:  ptr.Int32Ptr(1),
			BackoffLimit: ptr.Int32Ptr(1),
			Template: corev1.PodTemplateSpec{
				ObjectMeta: metav1.ObjectMeta{},
				Spec: corev1.PodSpec{
					ServiceAccountName: "qucheng",
					Containers: []corev1.Container{
						{
							Name:  "renewtls",
							Image: "hub.qucheng.com/platform/tlsrenew:2022",
							Env: []corev1.EnvVar{
								{
									Name:  "DOMAIN",
									Value: coreDomain,
								},
							},
						},
					},
					RestartPolicy: corev1.RestartPolicyOnFailure,
				},
			},
		},
	}
	_, err := jobclient.Create(context.TODO(), object, metav1.CreateOptions{})
	if err != nil {
		logger.Errorf("create renewtls job err: %v", err)
		return err
	}
	count := 1
	for {
		if count > 30 {
			break
		}
		if needrenew, _ := checkCertificate(domain, logger); needrenew {
			return nil
		}
		time.Sleep(time.Second * 10)
		count++
	}
	if count > 30 {
		logger.Error("create renewtls job timeout")
		return fmt.Errorf("renew tls timeout")
	}
	return nil
}
