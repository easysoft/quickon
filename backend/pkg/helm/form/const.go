// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package form

type FieldType string

const (
	IntField    FieldType = "int"
	StringField FieldType = "string"
	ChoiceField FieldType = "choice"
)
