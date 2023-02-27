// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package middleware

import (
	"context"
	"fmt"
	"github.com/pkg/errors"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"io/ioutil"
	"os"
	"sigs.k8s.io/yaml"

	"github.com/ergoapi/util/exhash"
	"github.com/google/uuid"
	"github.com/sethvargo/go-envconfig"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/external/mysql"
)

type MysqlManager struct {
}

func (mm *MysqlManager) ListInstances() (interface{}, error) {
	dbs, err := loadConfig()
	if err != nil {
		return nil, err
	}

	data := make([]map[string]string, 0)
	for k, opts := range dbs {
		item := map[string]string{
			"name": k,
			"host": opts.Host,
		}
		data = append(data, item)
	}
	return data, nil
}

func (mm *MysqlManager) CreateDB(body *model.Middleware) (interface{}, error) {
	dsn, cfg, err := readDSN(body.Instance)
	if err != nil {
		return nil, err
	}
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
	data.Host = cfg.Host
	data.Port = cfg.Port
	data.User = dbUser
	data.Password = dbPass
	return &data, nil
}

func (mm *MysqlManager) RecycleDB(body *model.Middleware) error {
	dsn, _, err := readDSN(body.Instance)
	if err != nil {
		return err
	}
	db, err := mysql.NewDB(dsn)
	if err != nil {
		return err
	}

	return db.Drop(body.Name, body.Name)
}

func readDSN(instance string) (dsn string, obj *BaseConfig, err error) {
	if instance != "" {
		dbs, e := loadConfig()
		if e != nil {
			return "", nil, e
		}

		if cfg, ok := dbs[instance]; ok {
			obj = &cfg
			dsn = fmt.Sprintf("%s:%s@tcp(%s:%d)/?charset=utf8&parseTime=True&loc=Local", cfg.User, cfg.Password, cfg.Host, cfg.Port)
			return
		} else {
			return "", nil, errors.New("db instance not found")
		}
	} else {
		var c DbEnvConfig
		if err = envconfig.Process(context.TODO(), &c); err != nil {
			return "", nil, err
		}
		obj = c.BaseConfig
		dsn = fmt.Sprintf("%s:%s@tcp(%s:%d)/?charset=utf8&parseTime=True&loc=Local", c.User, c.Password, c.Host, c.Port)
		return
	}
}

func loadConfig() (map[string]BaseConfig, error) {
	var content []byte
	var err error

	dbs := make(map[string]BaseConfig, 0)
	configFile := setupConfigDir() + "/" + constant.DbConfigFile

	if content, err = ioutil.ReadFile(configFile); err != nil {
		return nil, err
	}

	if err = yaml.Unmarshal(content, &dbs); err != nil {
		return nil, err
	}

	return dbs, nil
}

func setupConfigDir() string {
	if dir, ok := os.LookupEnv(constant.ENV_CONFIG_DIR); ok {
		return dir
	} else {
		return constant.ConfigDir
	}
}
