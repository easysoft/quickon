// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type SnippetConfig struct {
	Name       string                 `json:"name"`
	Namespace  string                 `json:"namespace"`
	Category   string                 `json:"category"`
	Values     map[string]interface{} `json:"values"`
	AutoImport bool                   `json:"auto_import,omitempty"`
}

type SnippetQuery struct {
	Name      string `form:"name" binding:"required"`
	Namespace string `form:"namespace"`
}
