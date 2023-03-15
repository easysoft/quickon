package gitea

import "errors"

var (
	ErrGroupNotFound error = errors.New("gitea group is not found")
)
