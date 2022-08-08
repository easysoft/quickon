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
		if fields != nil {
			for k, v := range fields {
				entry.Data[k] = v
			}
		}
	}
	return nil
}
