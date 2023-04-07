package factory

import "github.com/gin-gonic/gin"

var routeFactories = make([]RouterFactory, 0)

type RouterFactory interface {
	Apply(g *gin.RouterGroup)
}

func Register(f RouterFactory) {
	routeFactories = append(routeFactories, f)
}

func ApplyRoutes(g *gin.RouterGroup) {
	for _, f := range routeFactories {
		f.Apply(g)
	}
}
