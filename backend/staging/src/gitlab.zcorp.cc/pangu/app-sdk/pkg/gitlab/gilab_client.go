package gitlab

import (
	"net/http"

	"github.com/pkg/errors"
	gl "github.com/xanzy/go-gitlab"

	"gitlab.zcorp.cc/pangu/app-sdk/pkg/httplib"
)

type Client struct {
	*httplib.HTTPServer

	client *gl.Client
	Token  string

	baseAPI        string
	adminBasicAuth *httplib.BasicAuth
}

func New(s *httplib.HTTPServer) (*Client, error) {
	gClient, err := gl.NewBasicAuthClient(s.Username, s.Password,
		gl.WithBaseURL(httplib.GenerateHTTPServer(s, nil)+"/api/v4"))
	if err != nil {
		return nil, err
	}
	c := Client{
		HTTPServer: s,
		client:     gClient,
	}
	return &c, nil
}

func (c *Client) WithToken(token string) *Client {
	gClient, _ := gl.NewClient(token, gl.WithBaseURL(httplib.GenerateHTTPServer(c.HTTPServer, nil)+"/api/v4"))
	return &Client{
		HTTPServer: c.HTTPServer,
		client:     gClient,
	}
}

func (c *Client) ListUsers() ([]*gl.User, error) {
	data, res, err := c.client.Users.ListUsers(&gl.ListUsersOptions{})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}
	return data, nil
}

func (c *Client) GetUser(username string) (*gl.User, error) {
	users, res, err := c.client.Users.ListUsers(&gl.ListUsersOptions{
		Username: &username,
	})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}

	var user gl.User
	for _, u := range users {
		if u.Username == username {
			user = *u
			break
		}
	}
	return &user, nil
}

func (c *Client) CreateUser(userinfo *gl.CreateUserOptions) (*gl.User, error) {
	user, res, err := c.client.Users.CreateUser(userinfo)
	if e := checkError(res, err, http.StatusCreated); e != nil {
		return nil, e
	}

	return user, nil
}

func (c *Client) GetToken(userId int64, tokenName string) (*gl.ImpersonationToken, error) {
	tokens, res, err := c.client.Users.GetAllImpersonationTokens(int(userId), &gl.GetAllImpersonationTokensOptions{})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}

	var token gl.ImpersonationToken
	for _, t := range tokens {
		if t.Name == tokenName {
			token = *t
			break
		}
	}
	return &token, nil
}

func (c *Client) CreateToken(userId int64, tokenInfo *gl.CreateImpersonationTokenOptions) (*gl.ImpersonationToken, error) {
	token, res, err := c.client.Users.CreateImpersonationToken(int(userId), tokenInfo)
	if e := checkError(res, err, http.StatusCreated); e != nil {
		return nil, e
	}

	return token, nil
}

func (c *Client) DeleteToken(userId int64, tokenId int) error {
	res, err := c.client.Users.RevokeImpersonationToken(int(userId), tokenId)
	if e := checkError(res, err, http.StatusNoContent); e != nil {
		return err
	}
	return nil
}

func (c *Client) ListGroups() ([]*gl.Group, error) {
	groups, res, err := c.client.Groups.ListGroups(&gl.ListGroupsOptions{})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}
	return groups, nil
}

func (c *Client) GetGroup(groupName string) (*gl.Group, error) {
	groups, res, err := c.client.Groups.ListGroups(&gl.ListGroupsOptions{Search: gl.String(groupName)})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}

	var found bool
	var group gl.Group
	for _, g := range groups {
		if g.Name == groupName {
			group = *g
			found = true
			break
		}
	}

	if found {
		return &group, nil
	} else {
		return nil, ErrGroupNotFound
	}
}

func (c *Client) CreateGroup(groupInfo *gl.CreateGroupOptions) (*gl.Group, error) {
	group, res, err := c.client.Groups.CreateGroup(groupInfo)
	if e := checkError(res, err, http.StatusCreated); e != nil {
		return nil, e
	}
	return group, nil
}

func (c *Client) ListGroupRepos(groupId int64) ([]*gl.Project, error) {
	projects, res, err := c.client.Groups.ListGroupProjects(int(groupId), &gl.ListGroupProjectsOptions{})
	if e := checkError(res, err, http.StatusOK); e != nil {
		return nil, e
	}
	return projects, nil
}

func (c *Client) CreateGroupRepo(projectInfo *gl.CreateProjectOptions) (*gl.Project, error) {
	p, res, err := c.client.Projects.CreateProject(projectInfo)
	if e := checkError(res, err, http.StatusCreated); e != nil {
		return nil, e
	}
	return p, nil
}

func (c *Client) UpdateSettings(setting *gl.UpdateSettingsOptions) error {
	_, res, err := c.client.Settings.UpdateSettings(setting)
	if e := checkError(res, err, http.StatusOK); e != nil {
		return e
	}
	return nil
}

func checkError(res *gl.Response, err error, expect int) error {
	if err != nil {
		return err
	}

	if res.StatusCode != expect {
		e := errors.Wrapf(httplib.ErrUnexpectStatusCode, "code: %d", res.StatusCode)
		return e
	}

	return nil
}
