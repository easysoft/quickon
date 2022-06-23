// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package service

import (
	"context"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/app"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/middleware"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/namespace"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service/node"
)

func Apps(ctx context.Context, clusterName, namespace string) *app.Manager {
	return app.NewApps(ctx, "primary", namespace)
}

func Nodes(ctx context.Context, clusterName string) *node.Manager {
	return node.NewNodes(ctx, "primary")
}

func Namespaces(ctx context.Context, clusterName string) *namespace.Manager {
	return namespace.NewNamespaces(ctx, "primary")
}

func Middlewares(ctx context.Context) *middleware.Manager {
	return middleware.New(ctx)
}
