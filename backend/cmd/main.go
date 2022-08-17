// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package main

import (
	"github.com/spf13/cobra"
	_ "go.uber.org/automaxprocs"
	"k8s.io/kubectl/pkg/util/i18n"

	"gitlab.zcorp.cc/pangu/cne-api/cmd/serve"
	"gitlab.zcorp.cc/pangu/cne-api/cmd/version"
)

// @title CNE API
// @version 1.0.0
// @description CNE API.
// @contact.name QuCheng Pangu Team
// @license.name Z PUBLIC LICENSE 1.2
func main() {
	cmd := &cobra.Command{
		Use:   "cne-api",
		Short: i18n.T("cne-api"),
	}

	cmd.AddCommand(serve.NewCmdServe())
	cmd.AddCommand(version.NewCmdVersion())

	if err := cmd.Execute(); err != nil {
		panic(err)
	}
}
