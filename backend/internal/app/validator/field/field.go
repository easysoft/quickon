// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package field

import (
	"context"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"

	"github.com/go-playground/validator/v10"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
)

func New() map[string]validator.Func {
	return map[string]validator.Func{
		"cluster_exist":   clusterExist,
		"namespace_exist": namespaceExist,
		"version_format":  versionFormat,
	}
}

func clusterExist(fl validator.FieldLevel) bool {
	val := fl.Field().String()
	if val == "" {
		return true
	}

	return cluster.Exist(val)
}

func namespaceExist(fl validator.FieldLevel) bool {
	field := fl.Field()
	kind := field.Kind()

	topField, topKind, ok := fl.GetStructFieldOK()
	if !ok || topKind != kind {
		return false
	}

	// default reflect.String:
	clusterName := topField.String()
	if cluster.Exist(clusterName) {
		return service.Namespaces(context.TODO(), clusterName).Has(field.String())
	}
	return false
}

func versionFormat(fl validator.FieldLevel) bool {
	val := fl.Field().String()
	if val != "" {
		if val == "latest" {
			return true
		}
		return versionRegexSemantic.MatchString(val)
	}
	return true
}
