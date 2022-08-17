package instance

import (
	"reflect"

	"github.com/imdario/mergo"
)

func (i *Instance) GetAccountInfo() map[string]string {
	data := map[string]string{
		"username": "", "password": "",
	}

	values := i.release.Chart.Values
	mergo.Merge(&values, i.release.Config, mergo.WithOverwriteWithEmptyValue)

	if auth, ok := values["auth"]; ok {
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
