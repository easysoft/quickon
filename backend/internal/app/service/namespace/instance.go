// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package namespace

import v1 "k8s.io/api/core/v1"

type Instance struct {
	ns *v1.Namespace
}

func newInstance(ns *v1.Namespace) *Instance {
	return &Instance{ns: ns}
}

func (i *Instance) WriteAble() bool {
	labels := i.ns.Labels
	if v, ok := labels[labelCreatedBy]; ok {
		return v == labelValueOwner
	}
	return false
}
