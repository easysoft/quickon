package sonar

import (
	"fmt"
	"net/http"

	"github.com/parnurzeal/gorequest"
	"github.com/pkg/errors"
	"gitlab.zcorp.cc/pangu/app-sdk/pkg/httplib"
)

type Client struct {
	*httplib.HTTPServer

	client *gorequest.SuperAgent
	Token  string

	baseAPI        string
	adminBasicAuth *httplib.BasicAuth
}

func New(s *httplib.HTTPServer) *Client {
	c := Client{
		HTTPServer: s,
		client:     gorequest.New().SetDebug(s.Debug),
		baseAPI:    "/api",
		adminBasicAuth: &httplib.BasicAuth{
			Username: s.Username, Password: s.Password,
		},
	}
	return &c
}

func (c *Client) WithToken(token string) *Client {
	return &Client{
		HTTPServer: c.HTTPServer,
		client:     c.client,
		baseAPI:    c.baseAPI,
		adminBasicAuth: &httplib.BasicAuth{
			Username: token, Password: "",
		},
	}
}

func (c *Client) ListToken() (*ResponseSonarSearchToken, error) {
	var data ResponseSonarSearchToken
	path := c.baseAPI + "/user_tokens/search"

	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return nil, e
	}
	return &data, nil
}

func (c *Client) HasToken(tokenName string) (bool, error) {
	var data ResponseSonarSearchToken
	path := c.baseAPI + "/user_tokens/search"

	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return false, e
	}

	var exist bool
	for _, t := range data.Tokens {
		if t.Name == tokenName {
			exist = true
			break
		}
	}
	return exist, nil
}

func (c *Client) CreateToken(tokenName string) (*ResponseSonarToken, error) {
	var data ResponseSonarToken
	path := c.baseAPI + "/user_tokens/generate"

	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	form := fmt.Sprintf(`name=%s&type=%s`, tokenName, "GLOBAL_ANALYSIS_TOKEN")
	res, body, errs := c.client.Post(uri).Type(gorequest.TypeForm).SendString(form).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return nil, e
	}
	return &data, nil
}

func (c *Client) DeleteToken(tokenName string) error {
	path := c.baseAPI + "/user_tokens/revoke"

	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	form := fmt.Sprintf(`name=%s`, tokenName)
	res, body, errs := c.client.Post(uri).Type(gorequest.TypeForm).SendString(form).EndBytes()
	if e := checkError(res, body, errs, http.StatusNoContent); e != nil {
		return e
	}
	return nil
}

func checkError(res *http.Response, body []byte, errs []error, expect int) error {
	if len(errs) > 0 {
		return errs[len(errs)-1]
	}

	if res.StatusCode != expect {
		e := errors.Wrapf(httplib.ErrUnexpectStatusCode, "code: %d", res.StatusCode)
		return e
	}

	return nil
}
