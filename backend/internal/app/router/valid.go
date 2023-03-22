package router

import (
	"net/http"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/app"
)

type ExternalForm struct {
	Type     string      `json:"type"`
	Solution string      `json:"solution"`
	Data     interface{} `json:"data"`
}

func ValidExternalForm(c *gin.Context) {
	var (
		ctx  = c.Request.Context()
		err  error
		body ExternalForm
	)

	logger := getLogger(ctx)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	v, err := app.NewValidator(ctx, body.Type, body.Solution, body.Data)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if ok, code, e := v.IsValid(); !ok {
		logger.WithError(err).Error("validate failed")
		renderError(c, code, e)
		return
	}

	renderSuccess(c, http.StatusOK)
	return
}
