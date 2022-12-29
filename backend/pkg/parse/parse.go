package parse

import (
	"reflect"
	"strings"

	"github.com/spf13/cast"
)

type parser struct {
	base map[string]interface{}
}

func New(root map[string]interface{}) *parser {
	return &parser{base: root}
}

func (p *parser) GetString(path string) (string, bool) {
	o, err := p.parse(path)
	if err != nil {
		return "", false
	}

	otype := reflect.TypeOf(o)
	if otype.Kind() == reflect.String {
		return o.(string), true
	} else {
		return "", false
	}
}

func (p *parser) GetStringWithDefault(path, def string) string {
	v, ok := p.GetString(path)
	if !ok {
		return def
	}
	return v
}

func (p *parser) GetBool(path string) (bool, bool) {
	o, err := p.parse(path)
	if err != nil {
		return false, false
	}

	val, e := cast.ToBoolE(o)
	if e != nil {
		return false, false
	}
	return val, true
}

func (p *parser) GetBoolWithDefault(path string, def bool) bool {
	v, ok := p.GetBool(path)
	if !ok {
		return def
	}
	return v
}

func (p *parser) GetFloat64(path string) (float64, bool) {
	o, err := p.parse(path)
	if err != nil {
		return 0.0, false
	}

	val, e := cast.ToFloat64E(o)
	if e != nil {
		return cast.ToFloat64(0), false
	}
	return val, true
}

func (p *parser) GetFloat64WithDefault(path string, def float64) float64 {
	v, ok := p.GetFloat64(path)
	if !ok {
		return def
	}
	return v
}

func (p *parser) GetInt(path string) (int, bool) {
	o, err := p.parse(path)
	if err != nil {
		return 0, false
	}

	val, e := cast.ToIntE(o)
	if e != nil {
		return cast.ToInt(val), false
	}
	return val, true
}

func (p *parser) GetIntWithDefault(path string, def int) int {
	v, ok := p.GetInt(path)
	if !ok {
		return def
	}
	return v
}

func (p *parser) parse(path string) (interface{}, error) {
	var err error
	var ok bool
	var node map[string]interface{}
	var n interface{}
	var data interface{}

	frames := strings.Split(path, ".")
	node = p.base

	if len(frames) > 1 {
		for _, frame := range frames[0 : len(frames)-1] {
			n, ok = node[frame]
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
