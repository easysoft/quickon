// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package helm

import (
	"errors"
	"net/url"

	"helm.sh/helm/v3/pkg/cli"
	"helm.sh/helm/v3/pkg/repo"
)

func FindRepoName(repository string) (string, error) {
	var repoName string

	settings := cli.New()
	f, err := repo.LoadFile(settings.RepositoryConfig)
	if err != nil {
		return "", err
	}

	targetUrl, err := url.Parse(repository)
	if err != nil {
		return "", err
	}

	for _, entry := range f.Repositories {
		if entry.URL == repository {
			repoName = entry.Name
			break
		}

		entryUrl, err := url.Parse(entry.URL)
		if err != nil {
			continue
		}

		if entryUrl.Path == targetUrl.Path {
			repoName = entry.Name
			break
		}
	}

	if repoName == "" {
		err = errors.New("lookup repo failed")
		return repoName, err
	}
	return repoName, nil
}
