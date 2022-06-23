// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package tlog

import (
	"context"

	"k8s.io/klog/v2"
)

type contextKey struct{}

type Logger struct {
	fields []interface{}
}

func NewContext(ctx context.Context, fields ...interface{}) context.Context {
	return context.WithValue(ctx, contextKey{}, fields)
}

func WithCtx(ctx context.Context) *Logger {
	fields := ctx.Value(contextKey{}).([]interface{})
	return &Logger{fields: fields}
}

func (l *Logger) InfoS(msg string, keysAndValues ...interface{}) {
	keysAndValues = append(keysAndValues, l.fields...)
	klog.InfoSDepth(1, msg, keysAndValues...)
}

func (l *Logger) ErrorS(err error, msg string, keysAndValues ...interface{}) {
	keysAndValues = append(keysAndValues, l.fields...)
	klog.ErrorSDepth(1, err, msg, keysAndValues...)
}
