package router

import (
	"net/http"

	"github.com/gin-gonic/gin"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
)

func ClusterList(c *gin.Context) {
	data := cluster.List()
	renderJson(c, http.StatusOK, data)
}
