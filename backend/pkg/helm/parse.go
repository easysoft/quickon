// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package helm

import (
	"fmt"
	"reflect"
	"strconv"
)

func ParseValues(data map[string]interface{}) [][2]string {
	return parseMap(data, "")
}

func parseMap(data map[string]interface{}, previus string) [][2]string {
	var result [][2]string
	for key, val := range data {
		var v string
		k := joinKeys(previus, key)

		t := reflect.TypeOf(val)

		//fmt.Printf("key: %s, type: %s, value: %v\n", k, t.String(), val)
		switch t.Kind() {
		case reflect.String:
			v = val.(string)
		case reflect.Int:
			v = strconv.Itoa(val.(int))
		case reflect.Float64:
			v = fmt.Sprintf("%v", val)
		case reflect.Bool:
			v = strconv.FormatBool(val.(bool))
		case reflect.Map:
			rel := parseMap(val.(map[string]interface{}), k)
			result = append(result, rel...)
			continue
		default:
			continue
		}
		item := [2]string{k, v}
		result = append(result, item)
	}
	return result
}

func joinKeys(previous, current string) string {
	if previous == "" {
		return current
	} else {
		return previous + "." + current
	}
}
