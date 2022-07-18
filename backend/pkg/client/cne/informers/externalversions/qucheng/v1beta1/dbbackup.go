/*
Copyright 2022.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
// Code generated by informer-gen. DO NOT EDIT.

package v1beta1

import (
	"context"
	time "time"

	quchengv1beta1 "gitlab.zcorp.cc/pangu/cne-api/apis/qucheng/v1beta1"
	versioned "gitlab.zcorp.cc/pangu/cne-api/pkg/client/cne/clientset/versioned"
	internalinterfaces "gitlab.zcorp.cc/pangu/cne-api/pkg/client/cne/informers/externalversions/internalinterfaces"
	v1beta1 "gitlab.zcorp.cc/pangu/cne-api/pkg/client/cne/listers/qucheng/v1beta1"
	v1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	runtime "k8s.io/apimachinery/pkg/runtime"
	watch "k8s.io/apimachinery/pkg/watch"
	cache "k8s.io/client-go/tools/cache"
)

// DbBackupInformer provides access to a shared informer and lister for
// DbBackups.
type DbBackupInformer interface {
	Informer() cache.SharedIndexInformer
	Lister() v1beta1.DbBackupLister
}

type dbBackupInformer struct {
	factory          internalinterfaces.SharedInformerFactory
	tweakListOptions internalinterfaces.TweakListOptionsFunc
	namespace        string
}

// NewDbBackupInformer constructs a new informer for DbBackup type.
// Always prefer using an informer factory to get a shared informer instead of getting an independent
// one. This reduces memory footprint and number of connections to the server.
func NewDbBackupInformer(client versioned.Interface, namespace string, resyncPeriod time.Duration, indexers cache.Indexers) cache.SharedIndexInformer {
	return NewFilteredDbBackupInformer(client, namespace, resyncPeriod, indexers, nil)
}

// NewFilteredDbBackupInformer constructs a new informer for DbBackup type.
// Always prefer using an informer factory to get a shared informer instead of getting an independent
// one. This reduces memory footprint and number of connections to the server.
func NewFilteredDbBackupInformer(client versioned.Interface, namespace string, resyncPeriod time.Duration, indexers cache.Indexers, tweakListOptions internalinterfaces.TweakListOptionsFunc) cache.SharedIndexInformer {
	return cache.NewSharedIndexInformer(
		&cache.ListWatch{
			ListFunc: func(options v1.ListOptions) (runtime.Object, error) {
				if tweakListOptions != nil {
					tweakListOptions(&options)
				}
				return client.QuchengV1beta1().DbBackups(namespace).List(context.TODO(), options)
			},
			WatchFunc: func(options v1.ListOptions) (watch.Interface, error) {
				if tweakListOptions != nil {
					tweakListOptions(&options)
				}
				return client.QuchengV1beta1().DbBackups(namespace).Watch(context.TODO(), options)
			},
		},
		&quchengv1beta1.DbBackup{},
		resyncPeriod,
		indexers,
	)
}

func (f *dbBackupInformer) defaultInformer(client versioned.Interface, resyncPeriod time.Duration) cache.SharedIndexInformer {
	return NewFilteredDbBackupInformer(client, f.namespace, resyncPeriod, cache.Indexers{cache.NamespaceIndex: cache.MetaNamespaceIndexFunc}, f.tweakListOptions)
}

func (f *dbBackupInformer) Informer() cache.SharedIndexInformer {
	return f.factory.InformerFor(&quchengv1beta1.DbBackup{}, f.defaultInformer)
}

func (f *dbBackupInformer) Lister() v1beta1.DbBackupLister {
	return v1beta1.NewDbBackupLister(f.Informer().GetIndexer())
}
