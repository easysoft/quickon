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
