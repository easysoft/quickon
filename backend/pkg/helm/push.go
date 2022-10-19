// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package helm

import (
	"fmt"
	"io"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm/push"
)

func Push(path, repo string) error {
	cli, err := push.New(repo)
	if err != nil {
		return err
	}

	res, err := cli.UploadChartPackage(path, false)
	if err != nil {
		return err
	}

	if res.StatusCode > 300 {
		bodyByte, _ := io.ReadAll(res.Body)
		e := &PushErr{status: res.StatusCode, content: string(bodyByte)}
		return e
	}
	return err
}

type PushErr struct {
	status  int
	content string
}

func (e *PushErr) Error() string {
	return fmt.Sprintf("unexpect status %d with response %s", e.status, e.content)
}
