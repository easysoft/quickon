// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"reflect"

	"github.com/imdario/mergo"
)

func (i *Instance) GetAccountInfo(component string) map[string]string {
	data := map[string]string{
		"username": "", "password": "",
	}

	values := i.release.Chart.Values
	mergo.Merge(&values, i.release.Config, mergo.WithOverwriteWithEmptyValue)

	base := values
	if component != "" {
		if vals, ok := values[component]; ok {
			base, ok = vals.(map[string]interface{})
			if !ok {
				i.logger.Errorf("convert component values to map failed")
				return data
			}
		} else {
			return data
		}
	}

	if auth, ok := base["auth"]; ok {
		username := lookupFields(auth.(map[string]interface{}), "username", "user")
		password := lookupFields(auth.(map[string]interface{}), "password", "passwd")
		data["username"] = username
		data["password"] = password
	}

	return data
}

func lookupFields(m map[string]interface{}, names ...string) string {
	for _, name := range names {
		if v, ok := m[name]; ok {
			refv := reflect.ValueOf(v)
			if refv.Kind() == reflect.String {
				return v.(string)
			}
		}
	}
	return ""
}
