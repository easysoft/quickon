// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type QueryCluster struct {
	Cluster string `form:"cluster,default=primary" json:"cluster,default=primary"`
}

type QueryNamespace struct {
	QueryCluster
	Namespace string `form:"namespace" json:"namespace" binding:"required,namespace_exist=Cluster"`
}
