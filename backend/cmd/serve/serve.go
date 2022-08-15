// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package serve

import (
	"context"
	"github.com/spf13/viper"
	"os/signal"
	"strings"
	"syscall"

	"github.com/spf13/cobra"

	gins "gitlab.zcorp.cc/pangu/cne-api/internal/app/serve"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

func NewCmdServe() *cobra.Command {
	cmd := &cobra.Command{
		Use:   "serve",
		Short: "serve apiserver",
		Run:   serve,
	}

	viper.AutomaticEnv()
	viper.SetEnvKeyReplacer(strings.NewReplacer("-", "_"))

	flags := cmd.Flags()
	flags.String(logging.FlagLogLevel, "info", "logging level")
	viper.BindPFlag(logging.FlagLogLevel, flags.Lookup(logging.FlagLogLevel))
	return cmd
}

func serve(cmd *cobra.Command, args []string) {
	logger := logging.DefaultLogger().WithField("action", "initialize")
	ctx, stop := signal.NotifyContext(context.Background(), syscall.SIGTERM, syscall.SIGINT)
	go func() {
		<-ctx.Done()
		stop()
	}()

	logger.Info("start server")
	if err := gins.Serve(ctx, logger); err != nil {
		logger.Fatal("run serve: %v", err)
	}
}
