package helm

import (
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm/push"
	"io/ioutil"
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
		bodyByte, _ := ioutil.ReadAll(res.Body)
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
