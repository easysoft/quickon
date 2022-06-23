// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import "gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"

type Component interface {
	Name() string
	Kind() string
	Replicas() int32
	Status(stopped bool) constant.AppStatusType
	Age() int64
}
