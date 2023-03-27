package app

import (
	"context"
	"fmt"

	"gitlab.zcorp.cc/pangu/app-sdk/pkg/httplib"
	"gitlab.zcorp.cc/pangu/app-sdk/pkg/sonar"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/retcode"
)

type SonarqubeDevopsForm struct {
	Host  string `json:"host"`
	Token string `json:"token"`
}

type sonarqubeValidator struct {
	ctx      context.Context
	client   *sonar.Client
	solution string
	data     interface{}
}

func newSonarqubeValidator(ctx context.Context, solution string, data interface{}) (Validator, error) {
	switch solution {
	case solutionDevops:
		return &sonarqubeValidator{
			ctx:      ctx,
			solution: solution,
			data:     data,
		}, nil
	}
	return nil, fmt.Errorf("no implement method for %s", solution)
}

func (g *sonarqubeValidator) IsValid() (bool, int, error) {
	var code retcode.RetCode
	var err error
	var isValid bool
	var server *httplib.HTTPServer

	ms, err := parseModel(g.data)
	if err != nil {
		return false, int(retcode.InvalidFormContent), err
	}

	server, code, err = parseHttpServer(ms.Protocol, ms.Host, ms.Username, ms.Password)
	if err != nil {
		return false, int(code), err
	}

	g.client = sonar.New(server)

	switch g.solution {
	case solutionDevops:
		var m GiteaDevopsForm
		err = convertToStruct(g.data, &m)
		if err != nil {
			code = retcode.InvalidFormContent
			break
		}
		if _, err = g.client.WithToken(m.Token).ListToken(); err != nil {
			code = retcode.UnAuthenticatedFormToken
			break
		}
		isValid = true
	default:
		return true, int(code), nil
	}
	return isValid, int(code), err
}
