// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package httplib

// HTTPServer provide a common definition
type HTTPServer struct {
	Schema   string `envconfig:"SCHEMA" default:"http"`
	Host     string `envconfig:"HOST" default:"127.0.0.1"`
	Port     string `envconfig:"PORT" default:"8088"`
	UserName string
	PassWord string
	Debug    bool `envconfig:"DEBUG" default:"false"`
}
