// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

import (
	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
)

type ComponentDbServiceModel struct {
	ComponentBase
	Spec       quchengv1beta1.DbServiceSpec   `json:"spec,omitempty"`
	Status     quchengv1beta1.DbServiceStatus `json:"status,omitempty"`
	CreateTime int64                          `json:"create_time"`
	Source     ComponentBase                  `json:"source"`
}

type ComponentBase struct {
	Name      string `json:"name"`
	NameSpace string `json:"namespace"`
}
