// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package store

import (
	"fmt"
	"time"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	quchengclientset "github.com/easysoft/quickon-api/client/clientset/versioned"
	quchenginf "github.com/easysoft/quickon-api/client/informers/externalversions"
	quchenglister "github.com/easysoft/quickon-api/client/listers/qucheng/v1beta1"
	quchengv1beta1 "github.com/easysoft/quickon-api/qucheng/v1beta1"

	_ "github.com/vmware-tanzu/velero/pkg/apis/velero/v1"
	velerov1 "github.com/vmware-tanzu/velero/pkg/apis/velero/v1"
	veleroclientset "github.com/vmware-tanzu/velero/pkg/generated/clientset/versioned"
	veleroinformers "github.com/vmware-tanzu/velero/pkg/generated/informers/externalversions"
	velerolister "github.com/vmware-tanzu/velero/pkg/generated/listers/velero/v1"
	metaappsv1 "k8s.io/api/apps/v1"
	metabatchv1 "k8s.io/api/batch/v1"
	metav1 "k8s.io/api/core/v1"
	metanetworkv1 "k8s.io/api/networking/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/util/runtime"
	"k8s.io/client-go/dynamic"
	"k8s.io/client-go/informers"
	"k8s.io/client-go/kubernetes"
	appsv1 "k8s.io/client-go/listers/apps/v1"
	batchv1 "k8s.io/client-go/listers/batch/v1"
	v1 "k8s.io/client-go/listers/core/v1"
	networkv1 "k8s.io/client-go/listers/networking/v1"
	"k8s.io/client-go/rest"
	"k8s.io/client-go/tools/cache"
)

const (
	resyncPeriod = time.Minute * 10
)

type Informer struct {
	Nodes        cache.SharedIndexInformer
	Namespaces   cache.SharedIndexInformer
	Pods         cache.SharedIndexInformer
	Ingresses    cache.SharedIndexInformer
	Services     cache.SharedIndexInformer
	Endpoints    cache.SharedIndexInformer
	Secrets      cache.SharedIndexInformer
	ConfigMaps   cache.SharedIndexInformer
	Deployments  cache.SharedIndexInformer
	StatefulSets cache.SharedIndexInformer
	Jobs         cache.SharedIndexInformer

	DbService cache.SharedIndexInformer
	Db        cache.SharedIndexInformer

	Backups       cache.SharedIndexInformer
	Restores      cache.SharedIndexInformer
	DbBackups     cache.SharedIndexInformer
	VolumeBackups cache.SharedIndexInformer
}

func (i *Informer) Run(stopCh chan struct{}) {
	go i.Nodes.Run(stopCh)
	go i.Namespaces.Run(stopCh)
	go i.Pods.Run(stopCh)
	go i.Ingresses.Run(stopCh)
	go i.Services.Run(stopCh)
	go i.Endpoints.Run(stopCh)
	go i.ConfigMaps.Run(stopCh)
	go i.Secrets.Run(stopCh)
	go i.Deployments.Run(stopCh)
	go i.StatefulSets.Run(stopCh)
	go i.Jobs.Run(stopCh)
	go i.Backups.Run(stopCh)
	go i.Restores.Run(stopCh)
	go i.DbService.Run(stopCh)
	go i.Db.Run(stopCh)
	go i.DbBackups.Run(stopCh)
	go i.VolumeBackups.Run(stopCh)

	if !cache.WaitForCacheSync(stopCh,
		i.Nodes.HasSynced,
		i.Namespaces.HasSynced,
		i.Pods.HasSynced,
		i.Ingresses.HasSynced,
		i.Services.HasSynced,
		i.Endpoints.HasSynced,
		i.ConfigMaps.HasSynced,
		i.Secrets.HasSynced,
		i.Deployments.HasSynced,
		i.StatefulSets.HasSynced,
		i.Jobs.HasSynced,
		i.Backups.HasSynced,
		i.Restores.HasSynced,
		i.DbService.HasSynced,
		i.Db.HasSynced,
		i.DbBackups.HasSynced,
		i.VolumeBackups.HasSynced,
	) {
		runtime.HandleError(fmt.Errorf("timed out waiting for caches to sync"))
	}
}

type Lister struct {
	Nodes        v1.NodeLister
	Namespaces   v1.NamespaceLister
	Pods         v1.PodLister
	Ingresses    networkv1.IngressLister
	Services     v1.ServiceLister
	Endpoints    v1.EndpointsLister
	ConfigMaps   v1.ConfigMapLister
	Secrets      v1.SecretLister
	Deployments  appsv1.DeploymentLister
	StatefulSets appsv1.StatefulSetLister
	Jobs         batchv1.JobLister

	DbService     quchenglister.DbServiceLister
	Db            quchenglister.DbLister
	Backups       quchenglister.BackupLister
	Restores      quchenglister.RestoreLister
	DbBackups     quchenglister.DbBackupLister
	VolumeBackups velerolister.PodVolumeBackupLister
}

type Clients struct {
	Base    *kubernetes.Clientset
	Cne     *quchengclientset.Clientset
	Velero  *veleroclientset.Clientset
	Dynamic dynamic.Interface
}

type Storer struct {
	informers *Informer
	listers   *Lister
	Clients   *Clients
}

func NewStorer(config rest.Config) *Storer {
	s := &Storer{
		informers: &Informer{},
		listers:   &Lister{},
		Clients:   &Clients{},
	}

	logger := logging.DefaultLogger()

	if cs, err := kubernetes.NewForConfig(&config); err != nil {
		logger.WithError(err).Error("failed to prepare kubeclient")
	} else {
		s.Clients.Base = cs
		factory := informers.NewSharedInformerFactoryWithOptions(cs, resyncPeriod)

		s.informers.Nodes = factory.Core().V1().Nodes().Informer()
		s.listers.Nodes = factory.Core().V1().Nodes().Lister()

		s.informers.Namespaces = factory.Core().V1().Namespaces().Informer()
		s.listers.Namespaces = factory.Core().V1().Namespaces().Lister()

		s.informers.Pods = factory.Core().V1().Pods().Informer()
		s.listers.Pods = factory.Core().V1().Pods().Lister()

		s.informers.Ingresses = factory.Networking().V1().Ingresses().Informer()
		s.listers.Ingresses = factory.Networking().V1().Ingresses().Lister()

		s.informers.Services = factory.Core().V1().Services().Informer()
		s.listers.Services = factory.Core().V1().Services().Lister()

		s.informers.Endpoints = factory.Core().V1().Endpoints().Informer()
		s.listers.Endpoints = factory.Core().V1().Endpoints().Lister()

		s.informers.ConfigMaps = factory.Core().V1().ConfigMaps().Informer()
		s.listers.ConfigMaps = factory.Core().V1().ConfigMaps().Lister()

		s.informers.Secrets = factory.Core().V1().Secrets().Informer()
		s.listers.Secrets = factory.Core().V1().Secrets().Lister()

		s.informers.Deployments = factory.Apps().V1().Deployments().Informer()
		s.listers.Deployments = factory.Apps().V1().Deployments().Lister()

		s.informers.StatefulSets = factory.Apps().V1().StatefulSets().Informer()
		s.listers.StatefulSets = factory.Apps().V1().StatefulSets().Lister()

		s.informers.Jobs = factory.Batch().V1().Jobs().Informer()
		s.listers.Jobs = factory.Batch().V1().Jobs().Lister()
	}

	if cs, err := quchengclientset.NewForConfig(&config); err != nil {
		logger.WithError(err).Error("failed to prepare kubeclient")
	} else {
		s.Clients.Cne = cs
		factory := quchenginf.NewSharedInformerFactory(cs, resyncPeriod)

		s.informers.Backups = factory.Qucheng().V1beta1().Backups().Informer()
		s.listers.Backups = factory.Qucheng().V1beta1().Backups().Lister()

		s.informers.Restores = factory.Qucheng().V1beta1().Restores().Informer()
		s.listers.Restores = factory.Qucheng().V1beta1().Restores().Lister()

		s.informers.DbService = factory.Qucheng().V1beta1().DbServices().Informer()
		s.listers.DbService = factory.Qucheng().V1beta1().DbServices().Lister()

		s.informers.Db = factory.Qucheng().V1beta1().Dbs().Informer()
		s.listers.Db = factory.Qucheng().V1beta1().Dbs().Lister()

		s.informers.DbBackups = factory.Qucheng().V1beta1().DbBackups().Informer()
		s.listers.DbBackups = factory.Qucheng().V1beta1().DbBackups().Lister()
	}

	if cs, err := veleroclientset.NewForConfig(&config); err != nil {
		logger.WithError(err).Error("failed to prepare kubeclient")
	} else {
		s.Clients.Velero = cs

		factory := veleroinformers.NewSharedInformerFactory(cs, resyncPeriod)

		s.informers.VolumeBackups = factory.Velero().V1().PodVolumeBackups().Informer()
		s.listers.VolumeBackups = factory.Velero().V1().PodVolumeBackups().Lister()
	}

	if cs, err := dynamic.NewForConfig(&config); err != nil {
		logger.WithError(err).Error("failed to prepare dynamic kubeclient")
	} else {
		s.Clients.Dynamic = cs
	}

	return s
}

func (s *Storer) Informers() *Informer {
	return s.informers
}

func (s *Storer) Run(stopCh chan struct{}) {
	s.informers.Run(stopCh)
}

func (s *Storer) GetNodes(name string) (*metav1.Node, error) {
	return s.listers.Nodes.Get(name)
}

func (s *Storer) ListNodes(selector labels.Selector) ([]*metav1.Node, error) {
	return s.listers.Nodes.List(selector)
}

func (s *Storer) GetNamespace(name string) (*metav1.Namespace, error) {
	return s.listers.Namespaces.Get(name)
}

func (s *Storer) ListNamespaces(selector labels.Selector) ([]*metav1.Namespace, error) {
	return s.listers.Namespaces.List(selector)
}

func (s *Storer) GetPod(namespace, name string) (*metav1.Pod, error) {
	return s.listers.Pods.Pods(namespace).Get(name)
}

func (s *Storer) ListPods(namespace string, selector labels.Selector) ([]*metav1.Pod, error) {
	return s.listers.Pods.Pods(namespace).List(selector)
}

func (s *Storer) GetIngress(namespace, name string) (*metanetworkv1.Ingress, error) {
	return s.listers.Ingresses.Ingresses(namespace).Get(name)
}

func (s *Storer) ListIngresses(namespace string, selector labels.Selector) ([]*metanetworkv1.Ingress, error) {
	return s.listers.Ingresses.Ingresses(namespace).List(selector)
}

func (s *Storer) GetService(namespace, name string) (*metav1.Service, error) {
	return s.listers.Services.Services(namespace).Get(name)
}

func (s *Storer) ListServices(namespace string, selector labels.Selector) ([]*metav1.Service, error) {
	return s.listers.Services.Services(namespace).List(selector)
}

func (s *Storer) GetEndpoint(namespace, name string) (*metav1.Endpoints, error) {
	return s.listers.Endpoints.Endpoints(namespace).Get(name)
}

func (s *Storer) ListEndpoints(namespace string, selector labels.Selector) ([]*metav1.Endpoints, error) {
	return s.listers.Endpoints.Endpoints(namespace).List(selector)
}

func (s *Storer) GetConfigMap(namespace, name string) (*metav1.ConfigMap, error) {
	return s.listers.ConfigMaps.ConfigMaps(namespace).Get(name)
}

func (s *Storer) ListConfigMaps(namespace string, selector labels.Selector) ([]*metav1.ConfigMap, error) {
	return s.listers.ConfigMaps.ConfigMaps(namespace).List(selector)
}

func (s *Storer) GetSecret(namespace, name string) (*metav1.Secret, error) {
	return s.listers.Secrets.Secrets(namespace).Get(name)
}

func (s *Storer) ListSecrets(namespace string, selector labels.Selector) ([]*metav1.Secret, error) {
	return s.listers.Secrets.Secrets(namespace).List(selector)
}

func (s *Storer) GetDeployment(namespace string, name string) (*metaappsv1.Deployment, error) {
	return s.listers.Deployments.Deployments(namespace).Get(name)
}

func (s *Storer) ListDeployments(namespace string, selector labels.Selector) ([]*metaappsv1.Deployment, error) {
	return s.listers.Deployments.Deployments(namespace).List(selector)
}

func (s *Storer) GetStatefulSet(namespace string, name string) (*metaappsv1.StatefulSet, error) {
	return s.listers.StatefulSets.StatefulSets(namespace).Get(name)
}

func (s *Storer) ListStatefulSets(namespace string, selector labels.Selector) ([]*metaappsv1.StatefulSet, error) {
	return s.listers.StatefulSets.StatefulSets(namespace).List(selector)
}

func (s *Storer) GetJob(namespace string, name string) (*metabatchv1.Job, error) {
	return s.listers.Jobs.Jobs(namespace).Get(name)
}

func (s *Storer) ListJobs(namespace string, selector labels.Selector) ([]*metabatchv1.Job, error) {
	return s.listers.Jobs.Jobs(namespace).List(selector)
}

func (s *Storer) GetBackup(namespace string, name string) (*quchengv1beta1.Backup, error) {
	return s.listers.Backups.Backups(namespace).Get(name)
}

func (s *Storer) ListBackups(namespace string, selector labels.Selector) ([]*quchengv1beta1.Backup, error) {
	return s.listers.Backups.Backups(namespace).List(selector)
}

func (s *Storer) GetRestore(namespace string, name string) (*quchengv1beta1.Restore, error) {
	return s.listers.Restores.Restores(namespace).Get(name)
}

func (s *Storer) ListRestores(namespace string, selector labels.Selector) ([]*quchengv1beta1.Restore, error) {
	return s.listers.Restores.Restores(namespace).List(selector)
}

func (s *Storer) GetDbService(namespace string, name string) (*quchengv1beta1.DbService, error) {
	return s.listers.DbService.DbServices(namespace).Get(name)
}

func (s *Storer) ListDbService(namespace string, selector labels.Selector) ([]*quchengv1beta1.DbService, error) {
	return s.listers.DbService.DbServices(namespace).List(selector)
}

func (s *Storer) GetDb(namespace string, name string) (*quchengv1beta1.Db, error) {
	return s.listers.Db.Dbs(namespace).Get(name)
}

func (s *Storer) ListDb(namespace string, selector labels.Selector) ([]*quchengv1beta1.Db, error) {
	return s.listers.Db.Dbs(namespace).List(selector)
}

func (s *Storer) ListDbBackups(namespace string, selector labels.Selector) ([]*quchengv1beta1.DbBackup, error) {
	return s.listers.DbBackups.DbBackups(namespace).List(selector)
}

func (s *Storer) ListVolumeBackups(namespace string, selector labels.Selector) ([]*velerov1.PodVolumeBackup, error) {
	return s.listers.VolumeBackups.PodVolumeBackups(namespace).List(selector)
}
