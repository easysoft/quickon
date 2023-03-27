// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type ResourceModel struct {
	QueryNamespace
	Name string `form:"name" json:"name" binding:"required"`
}

type AppModel struct {
	ResourceModel
}

type AppCreateOrUpdateModel struct {
	AppModel
	Channel          string                 `json:"channel"`
	Chart            string                 `json:"chart" binding:"required"`
	Version          string                 `json:"version" binding:"version_format"`
	Username         string                 `json:"username,omitempty"`
	Settings         []StringSetting        `json:"settings"`
	SettingsMap      map[string]interface{} `json:"settings_map"`
	SettingsSnippets []string               `json:"settings_snippets"`
	ForceRestart     bool                   `json:"force_restart"`
}

type StringSetting struct {
	Key string `json:"key"`
	Val string `json:"value"`
}

type AppManageModel struct {
	AppModel
	Channel string `json:"channel"`
	Chart   string `json:"chart" binding:"required"`
}

type AppSettingMode struct {
	AppModel
	Mode string `form:"mode" binding:"oneof=list map"`
}

type AppListModel struct {
	QueryCluster
	Apps []NamespacedApp `json:"apps" binding:"required"`
}

type AppComponentModel struct {
	AppModel
	Component string `json:"component" form:"component" binding:"required"`
}

type AppWithComponentModel struct {
	AppModel
	Component string `json:"component" form:"component"`
}

//type AppSchemaModel struct {
//	AppComponentModel
//	Category string `json:"category" form:"category" binding:"required"`
//}

type AppBackupCreateModel struct {
	AppModel
	UserName string `form:"username" json:"username"`
}

type AppRestoreCreateModel struct {
	AppModel
	UserName   string `form:"username" json:"username"`
	BackupName string `json:"backup_name" form:"backup_name" binding:"required"`
}

type AppBackupModel struct {
	AppModel
	BackupName string `json:"backup_name" form:"backup_name" binding:"required"`
}

type AppRestoreModel struct {
	AppModel
	RestoreName string `json:"backup_name" form:"restore_name" binding:"required"`
}

type NamespacedApp struct {
	Namespace string `json:"namespace" binding:"required"`
	Name      string `json:"name" binding:"required"`
}

type DbServiceModel struct {
	QueryNamespace
	Name string `form:"name" json:"name" binding:"required"`
}

type MappingKeyType string

const (
	MappingKeyHelm   MappingKeyType = "helm"
	MappingKeySecret MappingKeyType = "secret"
)

type AppSettingMappingItem struct {
	Key  string         `json:"key"`
	Type MappingKeyType `json:"type"`
	Path string         `json:"path"`
}

type AppSettingMappings struct {
	NamespacedApp
	Mappings []AppSettingMappingItem `json:"mappings"`
}

type AppLogRequestModel struct {
	AppModel
	Component     string `form:"component"`
	PodName       string `form:"pod_name"`
	ContainerName string `form:"container_name"`
	Tail          int    `form:"tail,default=50"`
	Previous      bool   `form:"previous"`
	ShowTimestamp bool   `form:"show_timestamp"`
}
