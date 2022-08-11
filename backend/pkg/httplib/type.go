package httplib

// HTTPServer provide a common definition
type HTTPServer struct {
	Schema   string `envconfig:"SCHEMA" default:"http"`
	Host     string `envconfig:"HOST" default:"127.0.0.1"`
	Port     string `envconfig:"PORT" default:"8088"`
	UserName string
	PassWord string
	Debug    bool `envconfig:"DEBUG" default:"false"`
}
