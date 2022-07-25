// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package model

type AppModel struct {
	QueryNamespace
	Name string `form:"name" json:"name" binding:"required"`
}

type AppCreateOrUpdateModel struct {
	AppModel
	Channel     string                 `json:"channel"`
	Chart       string                 `json:"chart" binding:"required"`
	Version     string                 `json:"version" binding:"version_format"`
	Settings    []stringSetting        `json:"settings"`
	SettingsMap map[string]interface{} `json:"settings_map"`
}

type stringSetting struct {
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

type AppSchemaModel struct {
	AppComponentModel
	Category string `json:"category" form:"category" binding:"required"`
}

type AppWithUserModel struct {
	AppModel
	UserName string `form:"username" json:"username"`
}

type AppRestoreCreateModel struct {
	AppWithUserModel
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
