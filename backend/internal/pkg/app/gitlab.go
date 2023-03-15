package app

import (
	"context"
	"fmt"
	"gitlab.zcorp.cc/pangu/app-sdk/pkg/gitlab"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/retcode"
)

type GitlabDevopsForm struct {
	Host     string `json:"host"`
	Username string `json:"username,omitempty"`
	Password string `json:"password,omitempty"`
	Token    string `json:"token"`
}

type gitlabValidator struct {
	ctx      context.Context
	client   *gitlab.Client
	solution string
	data     interface{}
}

func newGitlabValidator(ctx context.Context, solution string, data interface{}) (Validator, error) {
	var serverModel ServerInfo
	err := convertToStruct(data, &serverModel)
	if err != nil {
		return nil, err
	}

	s, err := parseHttpServer(serverModel.Host, serverModel.Username, serverModel.Password)
	if err != nil {
		return nil, err
	}

	switch solution {
	case solutionDevops:
		client, e := gitlab.New(s)
		return &gitlabValidator{
			ctx:      ctx,
			client:   client,
			solution: solution,
			data:     data,
		}, e
	}
	return nil, fmt.Errorf("no implement method for %s", solution)
}

func (g *gitlabValidator) IsValid() (bool, int, error) {
	var code retcode.RetCode
	var err error
	var isValid bool

	switch g.solution {
	case solutionDevops:
		var m GiteaDevopsForm
		err = convertToStruct(g.data, &m)
		if err != nil {
			code = retcode.InvalidFormContent
			break
		}
		if _, err = g.client.WithToken(m.Token).ListUsers(); err != nil {
			code = retcode.UnAuthenticatedFormToken
			break
		}
		if _, err = g.client.ListGroups(); err != nil {
			code = retcode.UnAuthenticatedFormAccount
			break
		}
		isValid = true
	default:
		return true, int(code), nil
	}
	return isValid, int(code), err
}
