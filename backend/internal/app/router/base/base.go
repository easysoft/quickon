package base

import (
	"github.com/gin-gonic/gin"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/ext/router/factory"
	"net/http"
)

func init() {
	f := New()
	factory.Register(f)
}

type Router struct {
}

func New() factory.RouterFactory {
	return &Router{}
}

func (r *Router) Apply(g *gin.RouterGroup) {
	g.GET("/base/test", Test)
}

func Test(c *gin.Context) {
	c.JSON(http.StatusOK, "hello world, test base api")
}
