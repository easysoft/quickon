package httplib

import "errors"

var (
	ErrUnexpectStatusCode error = errors.New("unexpect status code")
	ErrUnauthorized             = errors.New("unauthorized")
	ErrForbidden                = errors.New("forbidden")
)
