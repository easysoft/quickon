package router

import (
	"context"
	"github.com/imdario/mergo"
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/snippet"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"strings"
)

func MergeSnippetConfigs(ctx context.Context, namespace string, snippetNames []string, logger logrus.FieldLogger) map[string]interface{} {
	var data map[string]interface{}
	for _, name := range snippetNames {
		logger.Debugf("try to parse snippet '%s'", name)
		if !strings.HasPrefix(name, snippet.NamePrefix) {
			name = snippet.NamePrefix + name
			logger.Debugf("use internal snippet name '%s'", name)
		}

		s, err := service.Snippets(ctx, "").Get(namespace, name)
		if err != nil {
			logger.WithError(err).Debugf("get snippet '%s' from namespace '%s' failed", name, namespace)
			logger.Infof("try load snippet '%s' from namespace '%s'", name, constant.DefaultRuntimeNamespace)
			s, err = service.Snippets(ctx, "").Get(name, constant.DefaultRuntimeNamespace)
			if err != nil {
				logger.WithError(err).Errorf("failed to get snippet '%s'", name)
			}
		}
		if s == nil {
			continue
		}

		err = mergo.Merge(&data, s.Values(), mergo.WithOverride)
		if err != nil {
			logger.WithError(err).WithField("snippet", name).Error("merge snippet config failed")
		}
	}
	return data
}
