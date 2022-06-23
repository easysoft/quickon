// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"strings"
	"time"

	valid "gitlab.zcorp.cc/pangu/cne-api/internal/app/validator"

	"github.com/gin-gonic/gin"
	"github.com/go-playground/validator/v10"
)

const (
	successMessage = "请求成功"
)

func renderError(c *gin.Context, code int, err error) {
	_ = c.Error(err)
	errMsg := err.Error()
	errs, ok := err.(validator.ValidationErrors)
	if ok {
		errors := make([]string, 0, len(errs))
		for _, e := range errs {
			errors = append(errors, e.Translate(valid.LoadTrans()))
		}
		errMsg = strings.Join(errors, ";")
	}
	c.JSON(200, gin.H{
		"code":      code,
		"message":   errMsg,
		"traceId":   c.GetHeader(HeaderTraceId),
		"timestamp": time.Now().Unix(),
	})
}

func renderMessage(c *gin.Context, code int, message string) {
	c.JSON(200, gin.H{
		"code":      code,
		"message":   message,
		"traceId":   c.GetHeader(HeaderTraceId),
		"timestamp": time.Now().Unix(),
	})
}

func renderSuccess(c *gin.Context, code int) {
	c.JSON(200, gin.H{
		"code":      200,
		"message":   successMessage,
		"traceId":   c.GetHeader(HeaderTraceId),
		"timestamp": time.Now().Unix(),
	})
}

func renderJson(c *gin.Context, code int, data interface{}) {
	c.JSON(200, gin.H{
		"code":      200,
		"message":   successMessage,
		"traceId":   c.Writer.Header().Get(HeaderTraceId),
		"timestamp": time.Now().Unix(),
		"data":      data,
	})
}

func renderJsonWithPagination(c *gin.Context, code int, data interface{}, p interface{}) {
	c.JSON(200, gin.H{
		"code":       200,
		"message":    successMessage,
		"timestamp":  time.Now().Unix(),
		"data":       data,
		"pagination": p,
	})
}

type response2xx struct {
	Success    bool        `json:"success"`
	Message    string      `json:"message"`
	Data       interface{} `json:"data"`
	Pagination interface{} `json:"pagination"`
}

type response5xx struct {
	Success    bool        `json:"success" default:"false"`
	Message    string      `json:"message"`
	Data       interface{} `json:"data"`
	Pagination interface{} `json:"pagination"`
}
