// Copyright (c) 2021 青岛易企天创管理咨询有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package mysql

import (
	"database/sql"
	"fmt"
	"strings"
	"time"

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"github.com/ergoapi/util/exstr"
	_ "github.com/go-sql-driver/mysql"
)

type DB struct {
	Dsn    string
	client *sql.DB
	logger logrus.FieldLogger
}

func NewDB(dsn string) (*DB, error) {
	var db DB

	logger := logging.DefaultLogger()
	db.Dsn = dsn
	dbclient, err := sql.Open("mysql", dsn)
	if err != nil {
		logger.WithError(err).Error("create sql client failed")
		return nil, err
	}
	dbclient.SetConnMaxLifetime(time.Minute * 3)
	dbclient.SetMaxOpenConns(10)
	dbclient.SetMaxIdleConns(10)
	db.client = dbclient

	db.logger = logger
	return &db, nil
}

func (db *DB) Create(dbname, dbuser, dbpass string) error {
	logger := db.logger
	_, err := db.client.Exec("CREATE DATABASE IF NOT EXISTS " + dbname + ";")
	if err != nil {
		logger.WithError(err).Errorf("create db %s failed", dbname)
		return fmt.Errorf("创建数据库失败")
	}
	_, err = db.client.Exec("use " + dbname)
	if err != nil {
		logger.WithError(err).Errorf("use db %s failed", dbname)
		return fmt.Errorf("创建数据库失败")
	}
	_, err = db.client.Exec("CREATE USER '" + dbuser + "'@'%' IDENTIFIED BY '" + dbpass + "';")
	if err != nil {
		logger.WithError(err).Errorf("create user %s failed", dbuser)
		return fmt.Errorf("创建用户失败")
	}
	grantCmd := fmt.Sprintf("GRANT ALL ON %s.* TO '%s'@'%%'", dbname, dbuser)
	_, err = db.client.Exec(grantCmd)
	if err != nil {
		logger.WithError(err).Errorf("grant user %s to db %s failed", dbuser, dbname)
		return fmt.Errorf("授权失败")
	}
	_, err = db.client.Exec("flush privileges;")
	if err != nil {
		logger.WithError(err).Error("flush privileges failed")
		return fmt.Errorf("刷新权限失败")
	}
	return nil
}

func (db *DB) Drop(dbname, dbuser string) error {
	logger := db.logger
	// 移除权限
	revokeCmd := fmt.Sprintf("REVOKE ALL ON %s.* FROM '%s'@'%%';", dbname, dbuser)
	_, err := db.client.Exec(revokeCmd)
	if err != nil {
		logger.WithError(err).Errorf("revoke user %s from %s failed", dbuser, dbname)
	}
	logger.Infof("revoked user %s from %s", dbuser, dbname)
	// 删除用户
	dropUserCmd := fmt.Sprintf("DROP USER IF EXISTS \"%v\";", dbuser)
	_, err = db.client.Exec(dropUserCmd)
	if err != nil {
		logger.WithError(err).Errorf("delete user %s failed", dbuser)
		return err
	}
	logger.Infof("deleted user %s", dbuser)
	// 删除数据库
	dropDBCmd := fmt.Sprintf("DROP DATABASE IF EXISTS %v;", dbname)
	_, err = db.client.Exec(dropDBCmd)
	if err != nil {
		logger.WithError(err).Errorf("delete db %s failed", dbname)
		return err
	}
	logger.Infof("deleted db %s", dbname)
	_, err = db.client.Exec("flush privileges;")
	if err != nil {
		logger.WithError(err).Error("flush privileges failed")
		return err
	}
	logger.Info("flush privileges success")
	return nil
}

func (db *DB) Ping() error {
	return db.client.Ping()
}

type DBCfg struct {
	Name string `json:"name"`
	User string `json:"user,omitempty"`
	Pass string `json:"pass,omitempty"`
	Host string `json:"host,omitempty"`
	Port int    `json:"port,omitempty"`
	Dsn  string `json:"dsn,omitempty"`
}

func (db *DBCfg) Check(dsn string) bool {
	if len(db.Name) == 0 || len(db.User) == 0 {
		return false
	}
	if len(dsn) == 0 {
		db.Host = "127.0.0.1"
		db.Port = 3306
	} else {
		dsn = strings.Split(dsn, ")/")[0] // root:ohhiughaa6He0Poje0aeFaiKae3Jeyie@tcp(127.0.0.1:3306)
		dsn = strings.Split(dsn, "tcp(")[1]
		d := strings.Split(dsn, ":")
		db.Host = d[0]
		db.Port = exstr.Str2Int(d[1])
	}
	return true
}

func (db *DB) Show() ([]DBCfg, error) {
	res, err := db.client.Query("SELECT schema_name as `database` FROM information_schema.schemata;")
	if err != nil {
		db.logger.WithError(err).Error("query schemata failed")
		return nil, err
	}
	dbs := make([]DBCfg, 0)
	for res.Next() {
		var dbname string
		if err := res.Scan(&dbname); err != nil {
			db.logger.WithError(err).Errorf("scan db failed")
			continue
		}
		dbs = append(dbs, DBCfg{
			Name: dbname,
		})
	}
	return dbs, nil
}

type Monitor struct {
	Name      string
	Row       int64
	DataSize  float64
	IndexSize float64
}

func (db *DB) Monitor() ([]Monitor, error) {
	res, err := db.client.Query("select table_schema as 'dbname',ifnull(sum(table_rows), 0) as 'dbrow',sum(truncate(data_length/1024/1024, 2)) as 'datasize',sum(truncate(index_length/1024/1024, 2)) as 'indexsize' from information_schema.tables group by table_schema order by sum(data_length) desc, sum(index_length) desc;")
	if err != nil {
		db.logger.WithError(err).Error("query db failed")
		return nil, err
	}
	ms := make([]Monitor, 0)
	for res.Next() {
		var name string
		var row int64
		var datasize, indexsize float64
		err := res.Scan(&name, &row, &datasize, &indexsize)
		if err != nil {
			db.logger.WithError(err).Error("monitor db failed")
			continue
		}
		m := Monitor{
			Name:      name,
			Row:       row,
			DataSize:  datasize,
			IndexSize: indexsize,
		}
		ms = append(ms, m)
	}
	return ms, nil
}

func (db *DB) Close() {
	if err := db.client.Close(); err != nil {
		db.logger.WithError(err).Error("close db failed")
	}
}
