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
// Code generated by client-gen. DO NOT EDIT.

package fake

import (
	"context"

	v1beta1 "gitlab.zcorp.cc/pangu/cne-api/apis/qucheng/v1beta1"
	v1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	labels "k8s.io/apimachinery/pkg/labels"
	schema "k8s.io/apimachinery/pkg/runtime/schema"
	types "k8s.io/apimachinery/pkg/types"
	watch "k8s.io/apimachinery/pkg/watch"
	testing "k8s.io/client-go/testing"
)

// FakeDbs implements DbInterface
type FakeDbs struct {
	Fake *FakeQuchengV1beta1
	ns   string
}

var dbsResource = schema.GroupVersionResource{Group: "qucheng", Version: "v1beta1", Resource: "dbs"}

var dbsKind = schema.GroupVersionKind{Group: "qucheng", Version: "v1beta1", Kind: "Db"}

// Get takes name of the db, and returns the corresponding db object, and an error if there is any.
func (c *FakeDbs) Get(ctx context.Context, name string, options v1.GetOptions) (result *v1beta1.Db, err error) {
	obj, err := c.Fake.
		Invokes(testing.NewGetAction(dbsResource, c.ns, name), &v1beta1.Db{})

	if obj == nil {
		return nil, err
	}
	return obj.(*v1beta1.Db), err
}

// List takes label and field selectors, and returns the list of Dbs that match those selectors.
func (c *FakeDbs) List(ctx context.Context, opts v1.ListOptions) (result *v1beta1.DbList, err error) {
	obj, err := c.Fake.
		Invokes(testing.NewListAction(dbsResource, dbsKind, c.ns, opts), &v1beta1.DbList{})

	if obj == nil {
		return nil, err
	}

	label, _, _ := testing.ExtractFromListOptions(opts)
	if label == nil {
		label = labels.Everything()
	}
	list := &v1beta1.DbList{ListMeta: obj.(*v1beta1.DbList).ListMeta}
	for _, item := range obj.(*v1beta1.DbList).Items {
		if label.Matches(labels.Set(item.Labels)) {
			list.Items = append(list.Items, item)
		}
	}
	return list, err
}

// Watch returns a watch.Interface that watches the requested dbs.
func (c *FakeDbs) Watch(ctx context.Context, opts v1.ListOptions) (watch.Interface, error) {
	return c.Fake.
		InvokesWatch(testing.NewWatchAction(dbsResource, c.ns, opts))

}

// Create takes the representation of a db and creates it.  Returns the server's representation of the db, and an error, if there is any.
func (c *FakeDbs) Create(ctx context.Context, db *v1beta1.Db, opts v1.CreateOptions) (result *v1beta1.Db, err error) {
	obj, err := c.Fake.
		Invokes(testing.NewCreateAction(dbsResource, c.ns, db), &v1beta1.Db{})

	if obj == nil {
		return nil, err
	}
	return obj.(*v1beta1.Db), err
}

// Update takes the representation of a db and updates it. Returns the server's representation of the db, and an error, if there is any.
func (c *FakeDbs) Update(ctx context.Context, db *v1beta1.Db, opts v1.UpdateOptions) (result *v1beta1.Db, err error) {
	obj, err := c.Fake.
		Invokes(testing.NewUpdateAction(dbsResource, c.ns, db), &v1beta1.Db{})

	if obj == nil {
		return nil, err
	}
	return obj.(*v1beta1.Db), err
}

// UpdateStatus was generated because the type contains a Status member.
// Add a +genclient:noStatus comment above the type to avoid generating UpdateStatus().
func (c *FakeDbs) UpdateStatus(ctx context.Context, db *v1beta1.Db, opts v1.UpdateOptions) (*v1beta1.Db, error) {
	obj, err := c.Fake.
		Invokes(testing.NewUpdateSubresourceAction(dbsResource, "status", c.ns, db), &v1beta1.Db{})

	if obj == nil {
		return nil, err
	}
	return obj.(*v1beta1.Db), err
}

// Delete takes name of the db and deletes it. Returns an error if one occurs.
func (c *FakeDbs) Delete(ctx context.Context, name string, opts v1.DeleteOptions) error {
	_, err := c.Fake.
		Invokes(testing.NewDeleteActionWithOptions(dbsResource, c.ns, name, opts), &v1beta1.Db{})

	return err
}

// DeleteCollection deletes a collection of objects.
func (c *FakeDbs) DeleteCollection(ctx context.Context, opts v1.DeleteOptions, listOpts v1.ListOptions) error {
	action := testing.NewDeleteCollectionAction(dbsResource, c.ns, listOpts)

	_, err := c.Fake.Invokes(action, &v1beta1.DbList{})
	return err
}

// Patch applies the patch and returns the patched db.
func (c *FakeDbs) Patch(ctx context.Context, name string, pt types.PatchType, data []byte, opts v1.PatchOptions, subresources ...string) (result *v1beta1.Db, err error) {
	obj, err := c.Fake.
		Invokes(testing.NewPatchSubresourceAction(dbsResource, c.ns, name, pt, data, subresources...), &v1beta1.Db{})

	if obj == nil {
		return nil, err
	}
	return obj.(*v1beta1.Db), err
}
