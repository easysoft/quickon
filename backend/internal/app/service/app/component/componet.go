// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

type Components struct {
	components []Component
}

func NewComponents() *Components {
	return &Components{
		components: make([]Component, 0),
	}
}

func (cs *Components) Add(c Component) {
	cs.components = append(cs.components, c)
}

func (cs *Components) Items() []Component {
	return cs.components
}
