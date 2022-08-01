// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"context"
	"github.com/sirupsen/logrus"

	"net/http"
	"strings"

	"github.com/ergoapi/util/environ"
	"github.com/gin-gonic/gin"
	"github.com/google/uuid"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

//Cors cors middleware
func Cors() gin.HandlerFunc {
	return func(c *gin.Context) {
		method := c.Request.Method
		origin := c.Request.Header.Get("Origin")
		if origin != "" {
			c.Header("Access-Control-Allow-Origin", "*")
			c.Header("Access-Control-Allow-Methods", "*")
			c.Header("Access-Control-Allow-Headers", "Origin, X-Requested-With, X-Auth-Token, Content-Type, Accept, Authorization")
			c.Header("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Request-Headers, Cache-Control, Content-Language, Content-Type")
			c.Header("Access-Control-Max-Age", "3600")
			c.Header("Access-Control-Allow-Credentials", "true")
			c.Set("content-type", "application/json")
		}
		if method == "OPTIONS" {
			c.AbortWithStatus(http.StatusNoContent)
			return
		}
		c.Next()
	}
}

func getToken(c *gin.Context) string {
	// 1. Authorization JWT Token 临时有效token
	token := c.Request.Header.Get("Authorization")
	if len(token) > 0 {
		return strings.Split(token, " ")[1]
	}
	// 2. Token 永久token
	token = c.Request.Header.Get(constant.Token)
	if len(token) > 0 {
		return token
	}
	return c.Query(constant.Token)
}

//auth auth middleware
func Auth() gin.HandlerFunc {
	return func(c *gin.Context) {
		uri := c.Request.RequestURI
		// 路由拦截 - 登录身份、资源权限判断等
		if !strings.HasPrefix(uri, "/api") {
			c.Next()
			return
		}
		// TODO 简单判断token是否一致, 后续支持jwt
		tokenValid := getToken(c)
		tokenDefault := environ.GetEnv("CNE_API_TOKEN", "gwaN4KynqNqQoPD7eN8s")
		if tokenValid != tokenDefault {
			renderMessage(c, http.StatusUnauthorized, "token is empty")
			c.Abort()
			return
		}
		c.Next()
	}
}

func Trace() gin.HandlerFunc {
	return func(c *gin.Context) {
		traceId := uuid.NewString()
		ctx := context.Background()
		ctx = logging.NewContext(ctx, logrus.Fields{"traceId": traceId})
		c.Request = c.Request.WithContext(ctx)
		c.Request.Header.Set(HeaderTraceId, traceId)
		c.Header(HeaderTraceId, traceId)
		c.Next()
	}
}
