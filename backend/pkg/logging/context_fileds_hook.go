// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package logging

import "github.com/sirupsen/logrus"

type ContextFieldsHook struct {
}

func (h *ContextFieldsHook) Levels() []logrus.Level {
	return logrus.AllLevels
}

func (h *ContextFieldsHook) Fire(entry *logrus.Entry) error {
	if entry.Context != nil {
		fields := WithContext(entry.Context)
		for k, v := range fields {
			entry.Data[k] = v
		}
	}
	return nil
}
