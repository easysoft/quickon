package market

import (
	"github.com/kelseyhightower/envconfig"
	"github.com/parnurzeal/gorequest"
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/httplib"
)

// Client for gallery with session cache
type Client struct {
	*httplib.HTTPServer

	client *gorequest.SuperAgent
	Token  string
}

// New Client
func New() *Client {
	server := httplib.HTTPServer{}
	_ = envconfig.Process("CNE_MARKET_API", &server)
	if server.Host == "" || server.Port == "" {
		panic("environment CNE_MARKET_API_HOST and CNE_MARKET_API_PORT must be set")
	}
	c := &Client{
		HTTPServer: &server,
		client:     gorequest.New().SetDebug(server.Debug),
	}
	return c
}

func (c *Client) SendAppAnalysis(body string) error {

	uri := httplib.GenerateURL(c.HTTPServer, "/api/market/analysis/put")

	resp, body, errs := c.client.Post(uri).SendString(body).End()
	if len(errs) != 0 {
		return errs[0]
	}
	logrus.Debug(resp.StatusCode, resp.Body)

	return nil
}
