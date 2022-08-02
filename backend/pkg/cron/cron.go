// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package cron

import (
	"github.com/robfig/cron/v3"
	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

var Cron *Client

type Client struct {
	client *cron.Cron
	logger logrus.FieldLogger
}

func New() *Client {
	return &Client{client: cron.New(), logger: logging.DefaultLogger()}
}

func (c *Client) Start() {
	c.logger.Info("start cron tasks")
	c.client.Start()
}

func (c *Client) Add(spec string, cmd func()) error {
	id, err := c.client.AddFunc(spec, cmd)
	c.logger.Infof("add cron: %v", id)
	return err
}

func (c *Client) Stop() {
	c.logger.Info("stop cron tasks")
	c.client.Stop()
}

func init() {
	Cron = New()
}
