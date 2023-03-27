// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type MiddlewareType struct {
	Type string `form:"type" binding:"required"`
}

type Middleware struct {
	Name      string `json:"name,omitempty" binding:"required"`
	Namespace string `json:"namespace,omitempty"`
	Type      string `json:"type,omitempty" binding:"required"` // 类型
	Instance  string `json:"instance,omitempty"`
}
