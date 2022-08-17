// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"context"
	"encoding/json"
	"errors"
	"net/http"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app/instance"

	"github.com/sirupsen/logrus"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

func LookupApp(c *gin.Context, q interface{}) (context.Context, *instance.Instance, int, error) {
	var (
		ctx = c.Request.Context()
		err error
		i   *instance.Instance
	)

	if c.Request.Method == "POST" {
		if err = c.ShouldBindJSON(q); err != nil {
			return ctx, nil, http.StatusBadRequest, err
		}
	}

	if err = c.ShouldBindQuery(q); err != nil {
		return ctx, nil, http.StatusBadRequest, err
	}

	query := parseAppModel(q)
	logger := getLogger(ctx)

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		logger.WithError(err).WithFields(logrus.Fields{
			"name": query.Name, "namespace": query.Namespace,
		}).Error(errGetAppFailed)
		if errors.Is(err, instance.ErrAppNotFound) {
			return ctx, i, http.StatusNotFound, err
		}
		return ctx, i, http.StatusInternalServerError, errors.New(errGetAppStatusFailed)
	}

	return ctx, i, http.StatusOK, nil
}

func parseAppModel(v interface{}) *model.AppModel {
	var app model.AppModel

	bytes, _ := json.Marshal(v)
	json.Unmarshal(bytes, &app)
	return &app
}

func getLogger(ctx context.Context) logrus.FieldLogger {
	return logging.DefaultLogger().WithContext(ctx)
}
