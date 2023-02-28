package router

import (
	"github.com/gin-gonic/gin"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"net/http"
)

func ClusterList(c *gin.Context) {
	data := cluster.List()
	renderJson(c, http.StatusOK, data)
}
