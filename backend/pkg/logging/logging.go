package logging

import (
	"fmt"
	"github.com/spf13/viper"
	"path"
	"runtime"
	"runtime/debug"
	"strings"
	"time"

	"github.com/sirupsen/logrus"
)

const FlagLogLevel = "log-level"

var (
	defaultLogger *logrus.Logger
)

func DefaultLogger() *logrus.Logger {
	if defaultLogger == nil {
		defaultLogger = NewLogger()
	}
	return defaultLogger
}

func NewLogger() *logrus.Logger {
	moduleName := readModuleName()

	logger := logrus.New()
	logger.SetReportCaller(true)
	logger.Formatter = &logrus.TextFormatter{
		DisableColors:    true,
		ForceQuote:       true,
		TimestampFormat:  time.RFC3339,
		FullTimestamp:    true,
		QuoteEmptyFields: true,
		CallerPrettyfier: func(f *runtime.Frame) (string, string) {
			var filename string
			if index := strings.Index(f.File, moduleName); index != -1 {
				filename = f.File[index+len(moduleName)+1:]
			} else {
				_, subName := path.Split(moduleName)
				if index = strings.Index(f.File, subName); index != -1 {
					filename = f.File[index+len(subName)+1:]
				}
			}

			if filename == "" {
				_, filename = path.Split(f.File)
			}
			return "", fmt.Sprintf("%s:%d", filename, f.Line)
		},
	}

	lv := viper.GetString(FlagLogLevel)
	level, err := logrus.ParseLevel(lv)
	if err != nil {
		logger.WithError(err).Fatalf("setup log level '%s' failed", lv)
	} else {
		logger.SetLevel(level)
		logger.Infof("setup log level to %s", lv)
	}

	logger.AddHook(&ContextFieldsHook{})
	return logger
}

func readModuleName() string {
	info, ok := debug.ReadBuildInfo()
	if !ok {
		return ""
	}

	var moduleName = ""
	for _, dep := range info.Deps {
		if dep.Version == "(devel)" {
			moduleName = dep.Path
		}
	}

	return moduleName
}
