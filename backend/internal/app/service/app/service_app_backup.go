// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"k8s.io/apimachinery/pkg/labels"
	"strings"
	"time"

	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
)

func (i *Instance) CreateBackup(username string) (interface{}, error) {
	currTime := time.Now()
	backupName := fmt.Sprintf("%s-backup-%d", i.name, currTime.Unix())
	backupReq := quchengv1beta1.Backup{
		ObjectMeta: metav1.ObjectMeta{
			Name: backupName,
			Labels: map[string]string{
				"app":     i.ChartName,
				"release": i.release.Name,
			},
		},
		Spec: quchengv1beta1.BackupSpec{
			Selector: map[string]string{
				"release": i.release.Name,
			},
			Namespace: i.namespace,
		},
	}
	if username != "" {
		backupReq.Annotations[constant.AnnotationResourceOwner] = username
	}

	data := struct {
		BackupName string `json:"backup_name"`
		CreateTime int64  `json:"create_time"`
	}{backupName, currTime.Unix()}

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Backups("cne-system").Create(i.ctx, &backupReq, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetBackupList() ([]model.AppRespBackup, error) {
	var result []model.AppRespBackup
	backups, err := i.ks.Store.ListBackups("cne-system", i.selector)
	if err != nil {
		return nil, err
	}

	for _, b := range backups {
		item := model.AppRespBackup{
			Name:       b.Name,
			Creator:    b.Annotations[constant.AnnotationResourceOwner],
			CreateTime: b.CreationTimestamp.Unix(),
			Status:     strings.ToLower(string(b.Status.Phase)),
			Message:    b.Status.Message,
			Restores:   make([]model.AppRespRestore, 0),
		}

		bkLabel, _ := labels.NewRequirement(constant.LabelBackupName, "==", []string{b.Name})
		restores, err := i.ks.Store.ListRestores("cne-system", i.selector.Add(*bkLabel))
		if err != nil {
			return result, err
		}

		for _, restore := range restores {
			r := model.AppRespRestore{
				Name:       restore.Name,
				Creator:    restore.Annotations[constant.AnnotationResourceOwner],
				CreateTime: restore.CreationTimestamp.Unix(),
				Status:     strings.ToLower(string(restore.Status.Phase)),
				Message:    restore.Status.Message,
			}
			item.Restores = append(item.Restores, r)
		}

		result = append(result, item)
	}

	return result, nil
}

func (i *Instance) GetBackupStatus(backupName string) (interface{}, error) {
	backup, err := i.ks.Store.GetBackup("cne-system", backupName)
	if err != nil {
		return nil, err
	}

	data := map[string]string{
		"status": string(backup.Status.Phase),
		"reason": backup.Status.Reason,
	}

	return data, nil
}

func (i *Instance) CreateRestore(backupName string, username string) (interface{}, error) {
	currTime := time.Now()
	restoreName := fmt.Sprintf("%s-restore-%d", i.name, currTime.Unix())
	restoreReq := quchengv1beta1.Restore{
		ObjectMeta: metav1.ObjectMeta{
			Name: restoreName,
			Labels: map[string]string{
				"app":                    i.ChartName,
				"release":                i.release.Name,
				constant.LabelBackupName: backupName,
			},
			Annotations: map[string]string{
				constant.AnnotationResourceOwner: username,
			},
		},
		Spec: quchengv1beta1.RestoreSpec{
			BackupName: backupName,
		},
	}
	if username != "" {
		restoreReq.Annotations[constant.AnnotationResourceOwner] = username
	}

	data := struct {
		RestoreName string `json:"restore_name"`
		CreateTime  int64  `json:"create_time"`
	}{backupName, currTime.Unix()}

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Restores("cne-system").Create(i.ctx, &restoreReq, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetRestoreStatus(restoreName string) (interface{}, error) {
	restore, err := i.ks.Store.GetRestore("cne-system", restoreName)
	if err != nil {
		return nil, err
	}

	data := map[string]string{
		"status": string(restore.Status.Phase),
		"reason": restore.Status.Reason,
	}

	return data, nil
}
