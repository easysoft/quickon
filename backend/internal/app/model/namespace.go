// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type NamespaceCreate struct {
	QueryCluster
	Name string `form:"name" json:"name" binding:"required"`
}

type NamespaceBase struct {
	QueryCluster
	Name string `form:"name" json:"name" binding:"required,namespace_exist=Cluster"`
}
