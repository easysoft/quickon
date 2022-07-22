// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type CompDbServiceListModel struct {
	Kind      string `form:"kind" json:"kind" binding:"required"`
	Namespace string `form:"namespace" json:"namespace"`
}

type CompDbServiceValidationModel struct {
	Name      string `form:"name" json:"kind" binding:"required"`
	Namespace string `form:"namespace" json:"namespace" binding:"required"`
	User      string `form:"user" json:"user" binding:"required"`
	Database  string `form:"database" json:"database" binding:"required"`
}

type ComponentDbServiceModel struct {
	ComponentBase
	Alias string `json:"alias"`
	Host  string `json:"host"`
	Port  int32  `json:"port"`
}

type ComponentBase struct {
	Name      string `json:"name"`
	NameSpace string `json:"namespace"`
}

type ComponentDbServiceValidResult struct {
	Host       string       `json:"host"`
	Port       int32        `json:"port"`
	Validation DbValidation `json:"validation"`
}

type DbValidation struct {
	User     bool `json:"user"`
	Database bool `json:"database"`
}