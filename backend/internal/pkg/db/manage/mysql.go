// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package manage

import (
	"database/sql"
	"fmt"

	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
	_ "github.com/go-sql-driver/mysql"
)

const (
	_mysqlDriver = "mysql"
)

type mysqlManage struct {
	meta DbServiceMeta
}

func newMysqlManager(meta DbServiceMeta) (DbManager, error) {
	return &mysqlManage{
		meta: meta,
	}, nil
}

func (m *mysqlManage) DbType() quchengv1beta1.DbType {
	return m.meta.Type
}

func (m *mysqlManage) ServerInfo() DbServerInfo {
	return &serverInfo{
		host: m.meta.Host, port: m.meta.Port,
	}
}

func (m *mysqlManage) IsValid(meta *DbMeta) error {
	dsn := m.genBusinessDsn(meta)
	dbClient, err := sql.Open(_mysqlDriver, dsn)
	if err != nil {
		return err
	}
	defer dbClient.Close()

	return dbClient.Ping()
}

func (m *mysqlManage) genAdminDsn() string {
	return fmt.Sprintf("%s:%s@tcp(%s:%d)/?charset=utf8mb4&parseTime=True&loc=Local",
		m.meta.AdminUser, m.meta.AdminPassword, m.meta.Host, m.meta.Port)
}

func (m *mysqlManage) genBusinessDsn(dbMeta *DbMeta) string {
	return fmt.Sprintf("%s:%s@tcp(%s:%d)/%s?charset=utf8mb4&parseTime=True&loc=Local",
		dbMeta.User, dbMeta.Password, m.meta.Host, m.meta.Port, dbMeta.Name)
}
