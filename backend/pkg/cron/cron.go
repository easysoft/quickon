// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package cron

import (
	"github.com/robfig/cron/v3"
	"k8s.io/klog/v2"
)

var Cron *Client

type Client struct {
	client *cron.Cron
}

func New() *Client {
	return &Client{client: cron.New()}
}

func (c *Client) Start() {
	klog.Info("start cron tasks")
	c.client.Start()
}

func (c *Client) Add(spec string, cmd func()) error {
	id, err := c.client.AddFunc(spec, cmd)
	klog.Infof("add cron: %v", id)
	return err
}

func (c *Client) Stop() {
	klog.Info("stop cron tasks")
	c.client.Stop()
}

func init() {
	Cron = New()
}
