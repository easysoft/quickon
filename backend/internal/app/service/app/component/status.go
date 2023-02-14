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

	// set default status to unknown
	appStatus = constant.AppStatusUnknown
	if replicas == 0 {
		// set workflow replicas to 0 by helm, contain stopping status
		if stopped && availableReplicas > 0 {
			appStatus = constant.AppStatusStopping
		}
		if availableReplicas == 0 {
			appStatus = constant.AppStatusStopped
		}
		return
	}

	/*
		If pods count is greater than replicas, return upgrading status
	*/
	if replicas > 0 {
		if availableReplicas > replicas {
			appStatus = constant.AppStatusUpgrading
			return
		} else if len(pods) > int(replicas) {
			// exclude evicted pods
			EvictedCount := 0
			for _, p := range pods {
				if p.Status.Reason == "Evicted" {
					EvictedCount += 1
				}
			}
			if (len(pods) - EvictedCount) > int(replicas) {
				appStatus = constant.AppStatusUpgrading
				return
			}
		}
	}

	// all pods are ready, return running status
	if replicas > 0 && updatedReplicas == replicas && readyReplicas == replicas {
		appStatus = constant.AppStatusRunning
		return
	}

	// set default to starting, continue decide
	if replicas > 0 && readyReplicas < replicas {
		appStatus = constant.AppStatusStarting
	}

	// determine status is abnormal or pulling
	// otherwise use the default status `starting`
	for _, pod := range pods {
		createTime := pod.CreationTimestamp
		for _, ctnStatus := range pod.Status.ContainerStatuses {
			if ctnStatus.Started == nil {
				break
			}
			if !*ctnStatus.Started {
				if ctnStatus.RestartCount >= 3 && time.Now().Unix()-createTime.Unix() > 300 {
					appStatus = constant.AppStatusAbnormal
					break
				}

				if ctnStatus.State.Waiting != nil {
					reason := ctnStatus.State.Waiting.Reason
					if reason == "ImagePullBackOff" {
						appStatus = constant.AppStatusPulling
						break
					}
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
