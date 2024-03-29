// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package schema

import (
	"github.com/imdario/mergo"
	"helm.sh/helm/v3/pkg/chart"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

func LoadCategories(currCh, parentCh *chart.Chart) Schemas {
	dest := make(Schemas, 0)
	if parentCh != nil {
		dest = loadSchemas(parentCh)
	}

	curr := loadSchemas(currCh)

	err := mergo.Merge(&dest, curr, mergo.WithOverwriteWithEmptyValue)
	if err != nil {
		logging.DefaultLogger().WithError(err).Error("merge map failed")
	}

	return dest
}
