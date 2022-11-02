// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package manage

import (
	"database/sql"
	"fmt"

	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
	_ "github.com/lib/pq"
)

const (
	_postgresqlDriver = "postgresql"
)

type postgresqlManage struct {
	meta DbServiceMeta
}

func newPostgresqlManager(meta DbServiceMeta) (DbManager, error) {
	return &postgresqlManage{
		meta: meta,
	}, nil
}

func (m *postgresqlManage) DbType() quchengv1beta1.DbType {
	return m.meta.Type
}

func (m *postgresqlManage) ServerInfo() DbServerInfo {
	return &serverInfo{
		host: m.meta.Host, port: m.meta.Port,
	}
}

func (m *postgresqlManage) IsValid(meta *DbMeta) error {
	dsn := m.genBusinessDsn(meta)
	dbClient, err := sql.Open(_postgresqlDriver, dsn)
	if err != nil {
		return err
	}
	defer dbClient.Close()

	return dbClient.Ping()
}

func (m *postgresqlManage) genAdminDsn() string {
	return fmt.Sprintf("postgres://%s:%s@%s:%d/postgres?sslmode=disable",
		m.meta.AdminUser, m.meta.AdminPassword, m.meta.Host, m.meta.Port)
}

func (m *postgresqlManage) genBusinessDsn(dbMeta *DbMeta) string {
	return fmt.Sprintf("postgres://%s:%s@%s:%d/%s?sslmode=disable",
		dbMeta.User, dbMeta.Password, m.meta.Host, m.meta.Port, dbMeta.Name)
}
