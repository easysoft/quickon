package router

import (
	"errors"
	"net/http"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"
)

func AppBackupCreate(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
	)
	if err = c.ShouldBindJSON(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data, err := i.CreateBackup()
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AppRestoreCreate(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err error
		op  model.AppBackupModel
		i   *app.Instance
	)
	if err = c.ShouldBindJSON(&op); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, op.Cluster, op.Namespace).GetApp(op.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", op.Cluster, "namespace", op.Namespace, "name", op.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data, err := i.CreateRestore(op.BackupName)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AppBackupStatus(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppBackupModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data, err := i.GetBackupStatus(query.BackupName)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AppRestoreStatus(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppRestoreModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data, err := i.GetRestoreStatus(query.RestoreName)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AppBackupList(c *gin.Context) {
	var (
		ctx = c.Request.Context()

		err   error
		query model.AppModel
		i     *app.Instance
	)
	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	i, err = service.Apps(ctx, query.Cluster, query.Namespace).GetApp(query.Name)
	if err != nil {
		tlog.WithCtx(ctx).ErrorS(err, errGetAppFailed, "cluster", query.Cluster, "namespace", query.Namespace, "name", query.Name)
		if errors.Is(err, app.ErrAppNotFound) {
			renderError(c, http.StatusNotFound, err)
			return
		}
		renderError(c, http.StatusInternalServerError, errors.New(errGetAppStatusFailed))
		return
	}

	data, err := i.GetBackupList()
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}
