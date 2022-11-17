// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package constant

type AppStatusType int

const (
	AppStatusUnknown    AppStatusType = iota + 1 // 未知
	AppStatusAbnormal                            // 异常
	AppStatusInit                                // 初始化
	AppStatusSuspending                          // 暂停中
	AppStatusSuspended                           // 暂停
	AppStatusStopping                            // 关闭中
	AppStatusStopped                             // 停止
	AppStatusPulling                             // 下载中
	AppStatusStarting                            // 启动中
	AppStatusUpgrading                           // 升级中
	AppStatusRunning                             // 运行中
)

type ClusterStatusType int

const (
	ClusterStatusNormal   ClusterStatusType = iota + 1 // 正常
	ClusterStatusAbnormal                              // 异常
)

var AppStatusMap = map[AppStatusType]string{
	AppStatusUnknown:    "unknown",
	AppStatusAbnormal:   "abnormal",
	AppStatusInit:       "initializing",
	AppStatusSuspending: "suspending",
	AppStatusSuspended:  "suspended",
	AppStatusStopping:   "stopping",
	AppStatusStopped:    "stopped",
	AppStatusPulling:    "pulling",
	AppStatusStarting:   "starting",
	AppStatusUpgrading:  "upgrading",
	AppStatusRunning:    "running",
}

var ClusterStatusMap = map[ClusterStatusType]string{
	ClusterStatusNormal:   "normal",
	ClusterStatusAbnormal: "abnormal",
}

const (
	AnnotationResourceOwner = "easycorp.io/resource_owner"
	AnnotationResourceAlias = "easycorp.io/resource_alias"
	AnnotationAppCreator    = "easycorp.io/app_creator"
	AnnotationAppChannel    = "easycorp.io/app_channel"
	AnnotationAppDomainKey  = "easycorp.io/app_domain_key"
)

const (
	LabelApp            = "app"
	LabelComponent      = "component"
	LabelApplication    = "easycorp.io/application"
	LabelBackupName     = "easycorp.io/backup_name"
	LabelGlobalDatabase = "easycorp.io/global_database"
)

const (
	LabelVeleroPvcUID = "velero.io/pvc-uid"
)
const FlagRuntimeNamespace = "pod-namespace"
