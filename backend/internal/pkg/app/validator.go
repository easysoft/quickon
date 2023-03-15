package app

import (
	"context"
	"errors"
)

type Validator interface {

	// IsValid return success result, http error code, and the error
	IsValid() (bool, int, error)
}

func NewValidator(ctx context.Context, t, solution string, data interface{}) (Validator, error) {
	switch t {
	case appGitea:
		return newGiteaValidator(ctx, solution, data)
	case appGitlab:
		return newGitlabValidator(ctx, solution, data)
	case appSonarqube:
		return newSonarqubeValidator(ctx, solution, data)
	default:
		return nil, errors.New("no support app type")
	}
}
