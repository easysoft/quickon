// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package manage

import (
	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
)

type DbManager interface {
	DbType() quchengv1beta1.DbType
	ServerInfo() DbServerInfo
	IsValid(meta *DbMeta) error
}

type DbServerInfo interface {
	Host() string
	Port() int32
	Address() string
}

type DbServiceMeta struct {
	Type          quchengv1beta1.DbType
	Host          string
	Port          int32
	AdminUser     string
	AdminPassword string
}

type DbMeta struct {
	Name     string
	User     string
	Password string
	Config   map[string]string
}
