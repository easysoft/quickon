package router

import (
	"context"
	"encoding/json"
	"errors"
	"net/http"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"
)

func LookupApp(c *gin.Context, q interface{}) (context.Context, *app.Instance, int, error) {
	var (
		ctx = c.Request.Context()
		err error
		i   *app.Instance
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

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
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
