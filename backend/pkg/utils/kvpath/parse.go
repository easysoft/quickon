package kvpath

import (
	"github.com/pkg/errors"
	"reflect"
	"strings"
)

var ErrPathParseFailed = errors.New("release path parse failed")

func ReadBool(node map[string]interface{}, path string) bool {
	var v bool
	data, err := read(node, path)
	if err != nil {
		return false
	}

	v, ok := data.(bool)
	if !ok {
		return false
	}

	return v
}

func ReadString(node map[string]interface{}, path string) string {
	var v string
	data, err := read(node, path)
	if err != nil {
		return ""
	}

	v, ok := data.(string)
	if !ok {
		return ""
	}

	return v
}

func Exist(node map[string]interface{}, path string) bool {
	_, err := read(node, path)
	if err != nil && err == ErrPathParseFailed {
		return false
	}
	return true
}

func read(node map[string]interface{}, path string) (interface{}, error) {
	var err error
	var ok bool
	var data interface{}

	frames := strings.Split(path, ".")

	if len(frames) > 1 {
		for _, frame := range frames[0 : len(frames)-1] {
			n, ok := node[frame]
			if !ok {
				err = ErrPathParseFailed
				break
			}
			ntype := reflect.TypeOf(n)
			if ntype.Kind() != reflect.Map {
				err = ErrPathParseFailed
				break
			}
			node = n.(map[string]interface{})
		}
		if err != nil {
			return nil, err
		}
		data, ok = node[frames[len(frames)-1]]
	} else {
		data, ok = node[path]
	}

	if !ok {
		return nil, ErrPathParseFailed
	}

	return data, nil
}
