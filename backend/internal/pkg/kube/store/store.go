// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package store

import (
	"fmt"
	"time"

	metaappsv1 "k8s.io/api/apps/v1"
	metav1 "k8s.io/api/core/v1"
	metanetworkv1 "k8s.io/api/networking/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/util/runtime"
	"k8s.io/client-go/informers"
	"k8s.io/client-go/kubernetes"
	appsv1 "k8s.io/client-go/listers/apps/v1"
	v1 "k8s.io/client-go/listers/core/v1"
	networkv1 "k8s.io/client-go/listers/networking/v1"
	"k8s.io/client-go/rest"
	"k8s.io/client-go/tools/cache"
	"k8s.io/klog/v2"
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
	Deployments  cache.SharedIndexInformer
	StatefulSets cache.SharedIndexInformer
}

func (i *Informer) Run(stopCh chan struct{}) {
	go i.Nodes.Run(stopCh)
	go i.Namespaces.Run(stopCh)
	go i.Pods.Run(stopCh)
	go i.Ingresses.Run(stopCh)
	go i.Services.Run(stopCh)
	go i.Endpoints.Run(stopCh)
	go i.Secrets.Run(stopCh)
	go i.Deployments.Run(stopCh)
	go i.StatefulSets.Run(stopCh)

	if !cache.WaitForCacheSync(stopCh,
		i.Nodes.HasSynced,
		i.Namespaces.HasSynced,
		i.Pods.HasSynced,
		i.Ingresses.HasSynced,
		i.Services.HasSynced,
		i.Endpoints.HasSynced,
		i.Secrets.HasSynced,
		i.Deployments.HasSynced,
		i.StatefulSets.HasSynced,
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
	Secrets      v1.SecretLister
	Deployments  appsv1.DeploymentLister
	StatefulSets appsv1.StatefulSetLister
}

type Clients struct {
	Base *kubernetes.Clientset
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

	if cs, err := kubernetes.NewForConfig(&config); err != nil {
		klog.ErrorS(err, "failed to prepare kubeclient")
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

		s.informers.Secrets = factory.Core().V1().Secrets().Informer()
		s.listers.Secrets = factory.Core().V1().Secrets().Lister()

		s.informers.Deployments = factory.Apps().V1().Deployments().Informer()
		s.listers.Deployments = factory.Apps().V1().Deployments().Lister()

		s.informers.StatefulSets = factory.Apps().V1().StatefulSets().Informer()
		s.listers.StatefulSets = factory.Apps().V1().StatefulSets().Lister()
	}

	return s
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
