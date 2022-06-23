// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package cluster

import "fmt"

type NotFound struct {
	Name string
	Err  error
}

func (e *NotFound) Error() string {
	return fmt.Sprintf("cluster '%s' not found", e.Name)
}

func (e *NotFound) Unwrap() error { return e.Err }

type AlreadyRegistered struct {
	Name string
	Err  error
}

func (e *AlreadyRegistered) Error() string {
	return fmt.Sprintf("cluster '%s' already registered", e.Name)
}

func (e *AlreadyRegistered) Unwrap() error { return e.Err }
