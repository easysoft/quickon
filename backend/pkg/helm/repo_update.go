// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package helm

import (
	"fmt"
	"sync"

	"github.com/pkg/errors"
	"helm.sh/helm/v3/pkg/cli"
	"helm.sh/helm/v3/pkg/getter"
	"helm.sh/helm/v3/pkg/repo"
)

var repoLocker *sync.Mutex

func init() {
	repoLocker = &sync.Mutex{}
}

type repoUpdateOptions struct {
	update               func([]*repo.ChartRepository, bool) error
	repoFile             string
	repoCache            string
	names                []string
	failOnRepoUpdateFail bool
}

func RepoUpdate() error {
	settings := cli.New()
	o := &repoUpdateOptions{update: updateCharts}
	o.repoFile = settings.RepositoryConfig
	o.repoCache = settings.RepositoryCache
	return o.run(settings)
}

func (o *repoUpdateOptions) run(settings *cli.EnvSettings) error {
	repoLocker.Lock()
	defer repoLocker.Unlock()

	f, err := repo.LoadFile(o.repoFile)
	if err != nil {
		return err
	}

	var repos []*repo.ChartRepository
	updateAllRepos := len(o.names) == 0

	if !updateAllRepos {
		// Fail early if the user specified an invalid repo to update
		if err := checkRequestedRepos(o.names, f.Repositories); err != nil {
			return err
		}
	}

	for _, cfg := range f.Repositories {
		if updateAllRepos || isRepoRequested(cfg.Name, o.names) {
			r, err := repo.NewChartRepository(cfg, getter.All(settings))
			if err != nil {
				return err
			}
			if o.repoCache != "" {
				r.CachePath = o.repoCache
			}
			repos = append(repos, r)
		}
	}

	return o.update(repos, o.failOnRepoUpdateFail)
}

func updateCharts(repos []*repo.ChartRepository, failOnRepoUpdateFail bool) error {
	var wg sync.WaitGroup
	var repoFailList []string
	for _, re := range repos {
		wg.Add(1)
		go func(re *repo.ChartRepository) {
			defer wg.Done()
			if _, err := re.DownloadIndexFile(); err != nil {
				repoFailList = append(repoFailList, re.Config.URL)
			}
		}(re)
	}
	wg.Wait()

	if len(repoFailList) > 0 && failOnRepoUpdateFail {
		return errors.New(fmt.Sprintf("Failed to update the following repositories: %s",
			repoFailList))
	}

	return nil
}

func checkRequestedRepos(requestedRepos []string, validRepos []*repo.Entry) error {
	for _, requestedRepo := range requestedRepos {
		found := false
		for _, repo := range validRepos {
			if requestedRepo == repo.Name {
				found = true
				break
			}
		}
		if !found {
			return errors.Errorf("no repositories found matching '%s'.  Nothing will be updated", requestedRepo)
		}
	}
	return nil
}

func isRepoRequested(repoName string, requestedRepos []string) bool {
	for _, requestedRepo := range requestedRepos {
		if repoName == requestedRepo {
			return true
		}
	}
	return false
}
