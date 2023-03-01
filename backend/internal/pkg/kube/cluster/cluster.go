// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package cluster

import (
	"fmt"
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
	v1 "k8s.io/api/core/v1"
	"k8s.io/client-go/tools/cache"
	"os"
	"path/filepath"
	"sync"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/metric"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/store"

	"k8s.io/client-go/rest"
	"k8s.io/client-go/tools/clientcmd"
)

var kubeClusters = make(map[string]*Cluster)
var lock = &sync.RWMutex{}

type Cluster struct {
	Name string

	Config       rest.Config
	Store        *store.Storer
	Metric       *metric.Metric
	Clients      *store.Clients
	ClientConfig clientcmd.ClientConfig

	inner     bool
	primary   bool
	reference string
}

func (c *Cluster) IsInner() bool {
	return c.inner
}

func (c *Cluster) IsPrimary() bool {
	return c.primary
}

func List() []map[string]interface{} {
	data := make([]map[string]interface{}, 0)
	for name, obj := range kubeClusters {
		item := map[string]interface{}{
			"name": name, "host": obj.Config.Host,
		}
		data = append(data, item)
	}
	return data
}

func Exist(name string) bool {
	lock.Lock()
	defer lock.Unlock()
	if name == "" {
		return true
	}
	_, ok := kubeClusters[name]
	return ok
}

func Get(name string) *Cluster {
	if name == "" {
		return kubeClusters[primaryClusterName]
	}
	lock.Lock()
	defer lock.Unlock()
	return kubeClusters[name]
}

func Remove(name string) {
	lock.Lock()
	defer lock.Unlock()
	if _, ok := kubeClusters[name]; ok {
		delete(kubeClusters, name)
	}
}

func add(name string, config rest.Config, clientConfig clientcmd.ClientConfig, inner, primary bool) *Cluster {
	s := store.NewStorer(config)
	m := metric.NewMetric(config)
	cluster := &Cluster{
		Name:         name,
		Config:       config,
		Store:        s,
		Metric:       m,
		Clients:      s.Clients,
		ClientConfig: clientConfig,
		inner:        inner,
		primary:      primary,
	}

	lock.Lock()
	defer lock.Unlock()

	kubeClusters[name] = cluster
	return cluster
}

func Init(stopChan chan struct{}) error {
	restCfg, err := loadPrimaryCluster()
	if err != nil {
		return err
	}

	primaryCluster := add(primaryClusterName, *restCfg, nil, true, true)

	c := &clusterManage{
		stopChan: stopChan,
		logger:   logging.DefaultLogger().WithField("action", "setupClusters"),
	}
	primaryCluster.Store.Informers().Secrets.AddEventHandler(cache.ResourceEventHandlerFuncs{
		AddFunc:    c.addClusterBySecret,
		UpdateFunc: c.updateClusterBySecret,
		DeleteFunc: c.removeClusterBySecret,
	})
	primaryCluster.Store.Run(stopChan)
	return nil
}

type clusterManage struct {
	logger   logrus.FieldLogger
	stopChan chan struct{}
}

// validSecret detect the secret has cluster label, read cluster name from annotations,
// the cluster-name can't use internal names.
// return the valid cluster name, and detect result.
func (c *clusterManage) validSecret(secret *v1.Secret) (string, bool) {
	var clusterName string
	c.logger.Debugf("try to read secret %s/%s", secret.Namespace, secret.Name)
	if _, ok := secret.Labels[constant.LabelCluster]; !ok {
		return "", false
	}

	if name, ok := secret.Annotations[constant.AnnotationClusterName]; !ok {
		c.logger.Warnf("miss annotation %s", constant.AnnotationClusterName)
		return "", false
	} else {
		if name == primaryClusterName {
			c.logger.Warnf("invalid cluster name %s", name)
			return "", false
		}
		clusterName = name
	}
	return clusterName, true
}

// addClusterBySecret add new cluster on program starting or a secret created
// the duplicate cluster will be ignored
func (c *clusterManage) addClusterBySecret(obj interface{}) {
	c.logger.Info("trigger add event")
	secret := obj.(*v1.Secret)
	clusterName, isValid := c.validSecret(secret)
	if !isValid {
		return
	}

	if Exist(clusterName) {
		c.logger.Warnf("duplicate cluster name %s", clusterName)
		return
	}

	c.addCluster(clusterName, secret)
}

// updateClusterBySecret update current cluster or
// add a new cluster if the name doesn't equal.
func (c *clusterManage) updateClusterBySecret(oldObj, newObj interface{}) {
	c.logger.Info("trigger update event")
	oldSecret := oldObj.(*v1.Secret)
	oldClusterName, isValid := c.validSecret(oldSecret)
	if !isValid {
		return
	}

	newSecret := newObj.(*v1.Secret)
	clusterName, isValid := c.validSecret(newSecret)
	if !isValid {
		return
	}

	if Exist(clusterName) {
		cluster := Get(clusterName)
		newRef := fmt.Sprintf("%s/%s", newSecret.Namespace, newSecret.Name)
		// Do cluster config modify must on the same secret.
		if cluster.reference != newRef {
			c.logger.Warnf("cluster %s is owned by newSecret %s, not %s", clusterName, cluster.reference, newRef)
			return
		}
		c.addCluster(clusterName, newSecret)
		return
	} else {
		c.addCluster(clusterName, newSecret)
		if oldClusterName != clusterName && Exist(oldClusterName) {
			Remove(oldClusterName)
			c.logger.Infof("old cluster %s was been removed", oldClusterName)
		}
	}
}

// removeClusterBySecret will remove a cluster
func (c *clusterManage) removeClusterBySecret(obj interface{}) {
	c.logger.Info("trigger remove event")
	secret := obj.(*v1.Secret)
	clusterName, isValid := c.validSecret(secret)
	if !isValid {
		return
	}

	if Exist(clusterName) {
		cluster := Get(clusterName)
		ref := fmt.Sprintf("%s/%s", secret.Namespace, secret.Namespace)
		// cluster will be removed only it's created by the same secret.
		if cluster.reference != ref {
			c.logger.Warnf("cluster %s is owned by secret %s, not %s", clusterName, cluster.reference, ref)
			return
		}
		Remove(clusterName)
		c.logger.Infof("cluster %s was been removed", clusterName)
	} else {
		c.logger.Infof("cluster %s is not exist, skip...")
	}

}

// addCluster read secret data, only support kube config style
// create cluster with the restConfig, start informer
func (c *clusterManage) addCluster(name string, secret *v1.Secret) {
	if content, ok := secret.Data[secretKeyContent]; ok {
		c.logger.Info("load kubeconfig")

		clientConfig, err := clientcmd.NewClientConfigFromBytes(content)
		if err != nil {
			c.logger.WithError(err).Error("load kubeconfig content failed")
			return
		}

		restCfg, err := clientConfig.ClientConfig()
		if err != nil {
			c.logger.WithError(err).Error("load rest config failed")
			return
		}

		newCluster := add(name, *restCfg, clientConfig, false, false)
		newCluster.reference = fmt.Sprintf("%s/%s", secret.Namespace, secret.Name)
		c.logger.Infof("start cluster informer sync")
		go newCluster.Store.Run(c.stopChan)
		c.logger.Infof("cluster %s is added", name)
	} else {
		c.logger.Errorf("'content' not found in secret")
		return
	}
}

func loadPrimaryCluster() (*rest.Config, error) {
	restCfg, err := rest.InClusterConfig()
	if err == nil {
		return restCfg, nil
	}

	userHome, err := os.UserHomeDir()
	if err != nil {
		return nil, err
	}
	configPath := filepath.Join(userHome, ".kube", "config")

	restCfg, err = clientcmd.BuildConfigFromFlags("", configPath)
	if err != nil {
		return nil, err
	}

	return restCfg, nil
}
