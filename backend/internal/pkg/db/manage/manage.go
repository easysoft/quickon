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
