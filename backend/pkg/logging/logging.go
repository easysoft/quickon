package logging

import (
	"fmt"
	"github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"gopkg.in/natefinch/lumberjack.v2"
	"io"
	"os"
	"path"
	"runtime"
	"runtime/debug"
	"strings"
)

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
		TimestampFormat:  "2006-01-02T15:04:05.999Z07:00",
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

	logger.SetOutput(prepareOutput())

	lv := viper.GetString(flagLogLevel)
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

func prepareOutput() io.Writer {
	logFile := viper.GetString(flagLogFile)
	if logFile == "" {
		return os.Stdout
	}

	fileHandler := lumberjack.Logger{
		Filename:   logFile,
		MaxSize:    100 * 1024 * 1024,
		MaxAge:     0,
		MaxBackups: viper.GetInt(flagLogFileBackups),
		LocalTime:  true,
		Compress:   true,
	}
	return &fileHandler
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
