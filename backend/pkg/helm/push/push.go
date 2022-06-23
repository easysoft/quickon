// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package push

import (
	"strings"

	cm "github.com/chartmuseum/helm-push/pkg/chartmuseum"
	"github.com/chartmuseum/helm-push/pkg/helm"
)

func New(repoName string) (*cm.Client, error) {
	var (
		err  error
		repo *helm.Repo
	)

	if repo, err = helm.GetRepoByName(repoName); err != nil {
		return nil, err
	}

	var url = strings.Replace(repo.Config.URL, "cm://", "https://", 1)
	return cm.NewClient(
		cm.URL(url),
		cm.Username(repo.Config.Username),
		cm.Password(repo.Config.Password),
	)
}
