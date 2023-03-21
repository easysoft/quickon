// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

import (
	v1 "k8s.io/api/core/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
)

type AppRespStatus struct {
	Status     string                   `json:"status"`
	Version    string                   `json:"version"`
	Age        int64                    `json:"age"`
	AccessHost string                   `json:"access_host"`
	Components []AppRespStatusComponent `json:"components"`
}

type AppRespStatusComponent struct {
	Name       string                 `json:"name"`
	Kind       string                 `json:"kind"`
	StatusCode constant.AppStatusType `json:"-"`
	Status     string                 `json:"status"`
	Replicas   int32                  `json:"replicas"`
	Age        int64                  `json:"age"`
}

type NamespacedAppRespStatus struct {
	Name       string `json:"name"`
	Namespace  string `json:"namespace"`
	Status     string `json:"status"`
	Version    string `json:"version"`
	Age        int64  `json:"age"`
	AccessHost string `json:"access_host"`
}

type NamespaceAppMetric struct {
	Namespace string     `json:"namespace"`
	Name      string     `json:"name"`
	Metrics   *AppMetric `json:"metrics"`
	Status    string     `json:"status"`
	Age       int64      `json:"age"`
}

type AppMetric struct {
	Cpu    ResourceCpu    `json:"cpu"`
	Memory ResourceMemory `json:"memory"`
}

type ResourceCpu struct {
	Usage float64 `json:"usage"`
	Limit float64 `json:"limit"`
}

type ResourceMemory struct {
	Usage int64 `json:"usage"`
	Limit int64 `json:"limit"`
}

type NodeMetric struct {
	Cpu    NodeResourceCpu    `json:"cpu"`
	Memory NodeResourceMemory `json:"memory"`
}

type NodeResourceCpu struct {
	Usage       float64 `json:"usage"`
	Capacity    float64 `json:"capacity"`
	Allocatable float64 `json:"allocatable"`
}

type NodeResourceMemory struct {
	Usage       int64 `json:"usage"`
	Capacity    int64 `json:"capacity"`
	Allocatable int64 `json:"allocatable"`
}

type ClusterMetric struct {
	Status    string     `json:"status"`
	NodeCount int        `json:"node_count"`
	Metrics   NodeMetric `json:"metrics"`
}

type Component struct {
	Name string `json:"name"`
}

type AppRespPvc struct {
	Name             string                          `json:"pvc_name"`
	VolumeName       string                          `json:"pvc_volume"`
	AccessModes      []v1.PersistentVolumeAccessMode `json:"pvc_access_modes"`
	StorageClassName string                          `json:"pvc_storage_class"`
	Size             float64                         `json:"pvc_storage_size"`
	Path             string                          `json:"pvc_path"`
}

type AppRespBackup struct {
	Name          string           `json:"name"`
	Creator       string           `json:"creator"`
	CreateTime    int64            `json:"create_time"`
	ChartName     string           `json:"chart_name,omitempty"`
	ChartVersion  string           `json:"chart_version,omitempty"`
	Status        string           `json:"status"`
	Message       string           `json:"message"`
	BackupDetails AppBackupDetails `json:"backup_details"`
	Restores      []AppRespRestore `json:"restores"`
}

type AppBackupDetails struct {
	DB  []AppDbBackupInfo  `json:"db"`
	PVC []AppPvcBackupInfo `json:"volume"`
}

type AppDbBackupInfo struct {
	DbType string  `json:"db_type"`
	DbName string  `json:"db_name"`
	Status string  `json:"status"`
	Cost   float64 `json:"cost"`
	Size   int64   `json:"size"`
}

type AppPvcBackupInfo struct {
	PvcName    string  `json:"pvc_name"`
	Volume     string  `json:"volume"`
	Status     string  `json:"status"`
	Cost       float64 `json:"cost"`
	TotalBytes int64   `json:"total_bytes"`
	DoneBytes  int64   `json:"doneBytes"`
}

type AppRespRestore struct {
	Name       string `json:"name"`
	Creator    string `json:"creator"`
	CreateTime int64  `json:"create_time"`
	Status     string `json:"status"`
	Message    string `json:"message"`
}

type AppRespAppDetail struct {
	Name      string                 `json:"name"`
	Namespace string                 `json:"namespace"`
	Chart     string                 `json:"chart"`
	Version   string                 `json:"version"`
	Channel   string                 `json:"channel"`
	Username  string                 `json:"username"`
	Values    map[string]interface{} `json:"values"`
}

type AppCustomSetting struct {
	Name    string      `json:"name"`
	Default interface{} `json:"default"`
	Label   string      `json:"label"`
	Desc    string      `json:"desc"`
}

type AppResDomain struct {
	InternalHost    string            `json:"internal_host"`
	AccessHost      string            `json:"access_host"`
	ExtraHosts      map[string]string `json:"extra_hosts"`
	LoadBalancerIPS map[string]string `json:"load_balancer_ips"`
}
