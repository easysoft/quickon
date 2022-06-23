// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"github.com/ergoapi/util/version"
	_ "github.com/ergoapi/util/version/prometheus"
	"github.com/gin-gonic/gin"
)

func ping(c *gin.Context) {
	v := version.Get()
	c.String(200, "pong "+v.Release)
}

func health(c *gin.Context) {
	v := version.Get()
	c.String(200, "health "+v.Release)
}
