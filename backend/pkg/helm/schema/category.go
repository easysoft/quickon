package schema

import (
	"github.com/imdario/mergo"
	"helm.sh/helm/v3/pkg/chart"
	"k8s.io/klog/v2"
)

func LoadCategories(currCh, parentCh *chart.Chart) Schemas {
	dest := make(Schemas, 0)
	if parentCh != nil {
		dest = loadSchemas(parentCh)
	}

	curr := loadSchemas(currCh)

	err := mergo.Merge(&dest, curr, mergo.WithOverwriteWithEmptyValue)
	if err != nil {
		klog.ErrorS(err, "merge map failed")
	}

	return dest
}
