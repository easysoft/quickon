// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package component

import (
	"time"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"

	v1 "k8s.io/api/core/v1"
)

func parseStatus(replicas, availableReplicas, updatedReplicas, readyReplicas int32,
	pods []*v1.Pod, stopped bool) (appStatus constant.AppStatusType) {

	appStatus = constant.AppStatusUnknown
	if replicas == 0 {
		if stopped && availableReplicas > 0 {
			appStatus = constant.AppStatusStopping
		}
		if availableReplicas == 0 {
			appStatus = constant.AppStatusStopped
		}
		return
	}

	if replicas > 0 && updatedReplicas == replicas && readyReplicas == replicas {
		appStatus = constant.AppStatusRunning
		return
	}

	if replicas > 0 && readyReplicas < replicas {
		appStatus = constant.AppStatusStarting
	}

	for _, pod := range pods {
		createTime := pod.CreationTimestamp
		for _, ctnStatus := range pod.Status.ContainerStatuses {
			if ctnStatus.Started == nil {
				break
			}
			if !*ctnStatus.Started {
				if ctnStatus.RestartCount >= 3 {
					appStatus = constant.AppStatusAbnormal
					break
				}

				if time.Now().Unix()-createTime.Unix() > 300 {
					appStatus = constant.AppStatusAbnormal
					break
				}
				//if ctnStatus.State.Waiting != nil && ctnStatus.State.Waiting.Reason == "CrashLoopBackOff" {
				//	appStatus = constant.AppStatusAbnormal
				//	break
				//}
			}
		}
	}
	return
}

func parseOldestAge(pods []*v1.Pod) int64 {
	var (
		nowUnix = time.Now().Unix()
		oldest  = nowUnix
	)
	for _, pod := range pods {
		startTime := pod.Status.StartTime
		if startTime == nil {
			continue
		}

		podAge := startTime.Unix()
		if podAge-oldest < 0 {
			oldest = podAge
		}
	}
	return nowUnix - oldest
}
