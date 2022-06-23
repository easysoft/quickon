// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package middleware

import (
	"context"
	"fmt"

	"github.com/ergoapi/util/exhash"
	"github.com/google/uuid"
	"github.com/sethvargo/go-envconfig"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/external/mysql"
)

type MysqlManager struct {
}

func (mm *MysqlManager) CreateDB(body *model.Middleware) (interface{}, error) {
	var c DbEnvConfig
	if err := envconfig.Process(context.TODO(), &c); err != nil {
		return nil, err
	}

	dsn := fmt.Sprintf("%s:%s@tcp(%s:%d)/?charset=utf8&parseTime=True&loc=Local", c.User, c.Password, c.Host, c.Port)
	db, err := mysql.NewDB(dsn)
	if err != nil {
		return nil, err
	}

	dbUser := body.Name
	if len(dbUser) > 32 {
		dbUser = dbUser[:32]
	}

	dbPass := exhash.MD5(body.Namespace + dbUser + uuid.New().String())

	err = db.Create(body.Name, dbUser, dbPass)
	if err != nil {
		return nil, err
	}

	data := DBConfig{
		Name: body.Name,
	}
	data.Host = c.Host
	data.Port = c.Port
	data.User = dbUser
	data.Password = dbPass
	return &data, nil
}

func (mm *MysqlManager) RecycleDB(body *model.Middleware) error {
	var c DbEnvConfig
	if err := envconfig.Process(context.TODO(), &c); err != nil {
		return err
	}

	dsn := fmt.Sprintf("%s:%s@tcp(%s:%d)/?charset=utf8&parseTime=True&loc=Local", c.User, c.Password, c.Host, c.Port)
	db, err := mysql.NewDB(dsn)
	if err != nil {
		return err
	}

	return db.Drop(body.Name, body.Name)
}
