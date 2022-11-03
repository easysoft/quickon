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
