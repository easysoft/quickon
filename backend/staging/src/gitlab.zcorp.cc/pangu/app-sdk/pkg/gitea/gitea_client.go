package gitea

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
		baseAPI:    "/api/v1",
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
		Token:      token,
	}
}

func (c *Client) ListUsers() ([]ResponseUserInfo, error) {
	var data []ResponseUserInfo

	path := c.baseAPI + "/admin/users"
	uri := httplib.GenerateURL(c.HTTPServer, path)

	res, body, errs := c.client.Get(uri).AppendHeader("Authorization", fmt.Sprintf("token %s", c.Token)).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return data, e
	}
	return data, nil
}

func (c *Client) HasUser(username string) (bool, error) {
	var data []ResponseUserInfo

	path := c.baseAPI + "/admin/users"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return false, e
	}

	var exist = false
	for _, user := range data {
		if user.Username == username {
			exist = true
			break
		}
	}
	return exist, nil
}

func (c *Client) CreateUser(userinfo *RequestUserCreate) (*ResponseUserInfo, error) {
	var data ResponseUserInfo

	path := c.baseAPI + "/admin/users"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Post(uri).SendStruct(userinfo).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusCreated); e != nil {
		return nil, e
	}

	return &data, nil
}

func (c *Client) HasToken(tokenName string, basicAuth *httplib.BasicAuth) (bool, error) {
	var data []ResponseTokenInfo

	path := c.baseAPI + "/users/" + basicAuth.Username + "/tokens"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, basicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return false, e
	}

	var exist = false
	for _, t := range data {
		if t.Name == tokenName {
			exist = true
			break
		}
	}
	return exist, nil
}

func (c *Client) CreateToken(tokenName string, basicAuth *httplib.BasicAuth) (*ResponseTokenInfo, error) {
	var data ResponseTokenInfo

	path := c.baseAPI + "/users/" + basicAuth.Username + "/tokens"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, basicAuth, path)

	res, body, errs := c.client.Post(uri).SendStruct(&RequestTokenCreate{Name: tokenName}).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusCreated); e != nil {
		return nil, e
	}

	return &data, nil
}

func (c *Client) DeleteToken(tokenName string, basicAuth *httplib.BasicAuth) error {
	path := c.baseAPI + "/users/" + basicAuth.Username + "/tokens/" + tokenName
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, basicAuth, path)

	res, body, errs := c.client.Delete(uri).EndBytes()
	if e := checkError(res, body, errs, http.StatusNoContent); e != nil {
		return e
	}

	return nil
}

func (c *Client) ListGroups() ([]ResponseOrgInfo, error) {
	var data []ResponseOrgInfo

	path := c.baseAPI + "/admin/orgs"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return nil, e
	}

	return data, nil
}

func (c *Client) GetOrg(groupName string) (*ResponseOrgInfo, error) {
	orgs, err := c.ListGroups()
	if err != nil {
		return nil, err
	}

	var found bool
	var org ResponseOrgInfo
	for _, g := range orgs {
		if g.Username == groupName {
			org = g
			found = true
			break
		}
	}

	if found {
		return &org, nil
	} else {
		return nil, ErrGroupNotFound
	}
}

func (c *Client) CreateGroup(g *RequestOrgCreate) (*ResponseOrgInfo, error) {
	var data ResponseOrgInfo

	path := c.baseAPI + "/orgs"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Post(uri).SendStruct(g).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusCreated); e != nil {
		return nil, e
	}

	return &data, nil
}

func (c *Client) ListGroupTeams(groupName string) ([]ResponseTeamInfo, error) {
	var data []ResponseTeamInfo

	path := c.baseAPI + "/orgs/" + groupName + "/teams"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return nil, e
	}

	return data, nil
}

func (c *Client) CreateGroupTeam(groupName string, t *RequestTeamCreate) (*ResponseTeamInfo, error) {
	var data ResponseTeamInfo

	path := c.baseAPI + "/orgs/" + groupName + "/teams"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Post(uri).SendStruct(t).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusCreated); e != nil {
		return nil, e
	}

	return &data, nil
}

func (c *Client) AddTeamMember(teamId int64, username string) error {
	path := c.baseAPI + fmt.Sprintf("/teams/%d/members/%s", teamId, username)
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Put(uri).EndBytes()
	return checkError(res, body, errs, http.StatusNoContent)
}

func (c *Client) ListGroupRepos(groupName string) ([]ResponseRepoInfo, error) {
	var data []ResponseRepoInfo

	path := c.baseAPI + "/orgs/" + groupName + "/repos"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Get(uri).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusOK); e != nil {
		return nil, e
	}

	return data, nil
}

func (c *Client) CreateGroupRepo(groupName string, t *RequestRepoCreate) (*ResponseRepoInfo, error) {
	var data ResponseRepoInfo

	path := c.baseAPI + "/orgs/" + groupName + "/repos"
	uri := httplib.GenerateURLWithBasicAuth(c.HTTPServer, c.adminBasicAuth, path)

	res, body, errs := c.client.Post(uri).SendStruct(t).EndStruct(&data)
	if e := checkError(res, body, errs, http.StatusCreated); e != nil {
		return nil, e
	}

	return &data, nil
}

func checkError(res *http.Response, body []byte, errs []error, expect int) error {
	if res != nil && res.StatusCode != expect {
		switch res.StatusCode {
		case http.StatusUnauthorized:
			return httplib.ErrUnauthorized
		case http.StatusForbidden:
			return httplib.ErrForbidden
		default:
			e := errors.Wrapf(httplib.ErrUnexpectStatusCode, "code: %d", res.StatusCode)
			return e
		}
	}

	if len(errs) > 0 {
		return errs[len(errs)-1]
	}

	return nil
}
