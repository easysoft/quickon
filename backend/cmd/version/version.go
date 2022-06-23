// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package version

import (
	"fmt"
	"runtime"

	"github.com/ergoapi/util/version"
	"github.com/spf13/cobra"
)

var versionTpl = `cne-api version:
 Version:           %v
 Go version:        %v
 Git commit:        %v
 Built:             %v
 OS/Arch:           %v
 Experimental:      true
`

func NewCmdVersion() *cobra.Command {
	cmd := &cobra.Command{
		Use:   "version",
		Short: "show build version",
		Run:   showVersion,
	}
	return cmd
}

func showVersion(cmd *cobra.Command, args []string) {
	v := version.Get()
	osarch := fmt.Sprintf("%v/%v", runtime.GOOS, runtime.GOARCH)
	fmt.Printf(versionTpl, v.Release, runtime.Version(), v.GitCommit, v.BuildDate, osarch)
}
