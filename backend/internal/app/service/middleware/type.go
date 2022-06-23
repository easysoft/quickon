// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package middleware

type BaseConfig struct {
	Host     string `json:"host" env:"HOST,required"`
	Port     int    `json:"port" env:"PORT,default=3306"`
	User     string `json:"user" env:"USER,required"`
	Password string `json:"password" env:"PASSWORD,required"`
}

type DbEnvConfig struct {
	*BaseConfig `env:",prefix=CLOUD_MYSQL_"`
}

type DBConfig struct {
	BaseConfig
	Name string `json:"name,omitempty"`
	Dsn  string `json:"dsn,omitempty"`
	UID  string `json:"uid,omitempty"`
}
