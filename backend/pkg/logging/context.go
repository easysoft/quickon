// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package logging

import "context"

type contextKey struct{}

func NewContext(ctx context.Context, fields map[string]interface{}) context.Context {
	return context.WithValue(ctx, contextKey{}, fields)
}

func WithContext(ctx context.Context) map[string]interface{} {
	fields, ok := ctx.Value(contextKey{}).(map[string]interface{})
	if !ok {
		return nil
	}
	return fields
}
