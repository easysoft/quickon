package gitlab

import "errors"

var (
	ErrGroupNotFound error = errors.New("gitlab group is not found")
)
