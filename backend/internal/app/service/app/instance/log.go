package instance

import (
	"errors"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"io/ioutil"
	v1 "k8s.io/api/core/v1"
	"k8s.io/klog/v2"
	"strconv"
	"strings"
)

func (i *Instance) Logs(op model.AppLogRequestModel) (LogLines, error) {
	comps := i.getComponents()
	var podName string
	var ctnName string
	var pod *v1.Pod
	var logTail = 50

	if op.PodName != "" {
		podName = op.PodName
	} else {
		targetCompName := i.ChartName
		if op.Component != "" {
			targetCompName = op.Component
		}
		find := false
		var pods []*v1.Pod

		for _, comp := range comps.Items() {
			if comp.CompName() == targetCompName {
				find = true
				pods = comp.Pods()
				break
			}
		}

		if !find {
			return nil, errors.New("component not found")
		}

		podName = pods[0].Name
		pod = pods[0]
	}
	i.logger.Debugln(podName)

	ctnName = pod.Spec.Containers[0].Name
	if op.ContainerName != "" && op.ContainerName != ctnName {
		find := false
		for _, ctn := range pod.Spec.Containers {
			if ctn.Name == op.ContainerName {
				find = true
				ctnName = op.ContainerName
				break
			}
		}
		if !find {
			return nil, errors.New("container not found")
		}
	}

	if op.Tail != 0 {
		logTail = op.Tail
	}

	return i.logs(podName, ctnName, op.Previous, logTail, op.ShowTimestamp)
}

func (i *Instance) logs(podName, ctnName string, previous bool, tailLines int, timeStamps bool) (LogLines, error) {
	req := i.Ks.Clients.Base.RESTClient().Get().Namespace(i.namespace).Resource("pods").
		Name(podName).SubResource("log").Prefix("/api/v1")
	req.Param("tailLines", strconv.Itoa(tailLines))
	req.Param("container", ctnName)
	req.Param("previous", strconv.FormatBool(previous))
	req.Param("timestamps", strconv.FormatBool(timeStamps))

	readCloser, err := req.Stream(i.Ctx)
	if err != nil {
		return nil, err
	}

	defer func() {
		_ = readCloser.Close()
	}()

	result, err := ioutil.ReadAll(readCloser)
	if err != nil {
		klog.Error(err.Error())
		return nil, err
	}

	return ToLogLines(string(result)), nil
}

type LogTimestamp string

type LogLines []LogLine

// A single log line. Split into timestamp and the actual content
type LogLine struct {
	Timestamp LogTimestamp `json:"timestamp"`
	Content   string       `json:"content"`
}

func ToLogLines(rawLogs string) LogLines {
	logLines := LogLines{}
	for _, line := range strings.Split(rawLogs, "\n") {
		if line != "" {
			startsWithDate := ('0' <= line[0] && line[0] <= '9') //2017-...
			idx := strings.Index(line, " ")
			if idx > 0 && startsWithDate {
				timestamp := LogTimestamp(line[0:idx])
				content := line[idx+1:]
				logLines = append(logLines, LogLine{Timestamp: timestamp, Content: content})
			} else {
				logLines = append(logLines, LogLine{Timestamp: LogTimestamp("0"), Content: line})
			}
		}
	}
	return logLines
}
