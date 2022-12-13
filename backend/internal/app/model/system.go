// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type ReqSystemUpdate struct {
	Channel string `json:"channel"`
	Version string `json:"version"`
}

type ReqSmtpAuth struct {
	Host string `json:"host"`
	Port string `json:"port"`
	User string `json:"user"`
	Pass string `json:"pass"`
}

// ReqSystemQLB 负载均衡配置
type ReqSystemQLB struct {
	QueryNamespace
	Name   string `json:"name"`
	IPPool string `json:"ippool"`
}
