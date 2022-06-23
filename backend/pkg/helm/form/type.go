// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package form

type DynamicForm struct {
	Sections []Component `yaml:"sections"`
}

type Component struct {
	Name   string       `yaml:"name"`
	Label  string       `yaml:"label"`
	Groups []FieldGroup `yaml:"groups"`
}

type FieldGroup struct {
	Name   string  `yaml:"name"`
	Fields []Field `yaml:"fields"`
}

type Field struct {
	Path     string    `yaml:"path"`
	Name     string    `yaml:"name"`
	Type     FieldType `yaml:"type"`
	Default  string    `yaml:"default"`
	Disabled bool      `yaml:"disabled"`
	Hidden   bool      `yaml:"hidden"`
}
