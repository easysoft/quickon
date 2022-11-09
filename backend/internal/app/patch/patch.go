// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package patch

import "context"

var actions []func(ctx context.Context) error

func register(f func(ctx context.Context) error) {
	actions = append(actions, f)
}

func Run(ctx context.Context) error {
	for _, f := range actions {
		_ = f(ctx)
	}
	return nil
}
