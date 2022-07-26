// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"context"
	"gopkg.in/yaml.v3"
	"helm.sh/helm/v3/pkg/cli/values"
	"io/ioutil"
	"os"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/helm"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/tlog"
)

type Manager struct {
	ctx context.Context

	clusterName string
	ks          *cluster.Cluster
	namespace   string
}

func NewApps(ctx context.Context, clusterName, namespace string) *Manager {
	return &Manager{
		ctx:         ctx,
		clusterName: clusterName, namespace: namespace,
		ks: cluster.Get(clusterName),
	}
}

func (m *Manager) Install(name string, body model.AppCreateOrUpdateModel) error {
	h, err := helm.NamespaceScope(m.namespace)
	if err != nil {
		return err
	}

	var settings = make([]string, len(body.Settings))
	for _, s := range body.Settings {
		settings = append(settings, s.Key+"="+s.Val)
	}
	options := &values.Options{Values: settings}
	tlog.WithCtx(m.ctx).InfoS("build install settings", "namespace", m.namespace, "name", name, "settings", settings)

	if len(body.SettingsMap) > 0 {
		tlog.WithCtx(m.ctx).InfoS("build install settings", "namespace", m.namespace, "name", name, "settings_map", body.SettingsMap)
		f, err := writeValuesFile(body.SettingsMap)
		if err != nil {
			tlog.WithCtx(m.ctx).ErrorS(err, "write values file failed")
		}
		defer os.Remove(f)
		options.ValueFiles = []string{f}
	}

	if err = helm.RepoUpdate(); err != nil {
		tlog.WithCtx(m.ctx).ErrorS(err, "helm update repo failed", "namespace", m.namespace, "name", name)
		return err
	}

	_, err = h.Install(name, genChart(body.Channel, body.Chart), body.Version, options)
	if err != nil {
		tlog.WithCtx(m.ctx).ErrorS(err, "helm install failed", "namespace", m.namespace, "name", name)
		if _, e := h.GetRelease(name); e == nil {
			tlog.WithCtx(m.ctx).InfoS("recycle incomplete release")
			_ = h.Uninstall(name)
		}
	}
	return err
}

func (m *Manager) UnInstall(name string) error {
	h, err := helm.NamespaceScope(m.namespace)
	if err != nil {
		return err
	}

	err = h.Uninstall(name)
	return err
}

func (m *Manager) GetApp(name string) (*Instance, error) {
	app := newApp(m.ctx, m, name)
	if app.release == nil {
		return nil, ErrAppNotFound
	}

	app.prepare()
	return app, nil
}

func writeValuesFile(data map[string]interface{}) (string, error) {
	f, err := ioutil.TempFile("/tmp", "values.******.yaml")
	if err != nil {
		return "", err
	}
	vars, err := yaml.Marshal(data)
	if err != nil {
		return "nil", err
	}
	_, err = f.Write(vars)
	if err != nil {
		return "nil", err
	}
	_ = f.Close()
	return f.Name(), nil
}
