package router

import (
	"net/http"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
)

func AppBackupCreate(c *gin.Context) {
	var (
		op model.AppModel
	)

	_, i, code, err := LookupApp(c, &op)
	if err != nil {
		renderError(c, code, err)
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
		op model.AppBackupModel
	)

	_, i, code, err := LookupApp(c, &op)
	if err != nil {
		renderError(c, code, err)
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
		query model.AppBackupModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
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
		query model.AppRestoreModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
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
		query model.AppModel
	)

	_, i, code, err := LookupApp(c, &query)
	if err != nil {
		renderError(c, code, err)
		return
	}

	data, err := i.GetBackupList()
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}
