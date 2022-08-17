package instance

import "errors"

var (
	ErrAppNotFound = errors.New("release not found")

	ErrPathParseFailed = errors.New("release path parse failed")
)
