// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package app

import (
	"fmt"
	"strings"
	"time"

	quchengv1beta1 "github.com/easysoft/quikon-api/qucheng/v1beta1"
	velerov1 "github.com/vmware-tanzu/velero/pkg/apis/velero/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
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
			Annotations: map[string]string{},
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

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Backups(constant.DefaultRuntimeNamespace).Create(i.ctx, &backupReq, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetBackupList() ([]model.AppRespBackup, error) {
	var result []model.AppRespBackup
	backups, err := i.ks.Store.ListBackups(constant.DefaultRuntimeNamespace, i.selector)
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

		bkReq, _ := labels.NewRequirement(constant.LabelBackupName, "==", []string{b.Name})
		bkLabel := i.selector.Add(*bkReq)

		dbBackups, _ := i.ks.Store.ListDbBackups(constant.DefaultRuntimeNamespace, labels.NewSelector().Add(*bkReq))
		volumeBackups, _ := i.ks.Store.ListVolumeBackups(constant.DefaultRuntimeNamespace, labels.NewSelector().Add(*bkReq))

		item.BackupDetails = i.statisticBackupDetail(dbBackups, volumeBackups)

		restores, err := i.ks.Store.ListRestores(constant.DefaultRuntimeNamespace, bkLabel)
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

func (i *Instance) statisticBackupDetail(dbs []*quchengv1beta1.DbBackup, volumes []*velerov1.PodVolumeBackup) model.AppBackupDetails {
	data := model.AppBackupDetails{
		DB:  make([]model.AppDbBackupInfo, 0),
		PVC: make([]model.AppPvcBackupInfo, 0),
	}

	for _, db := range dbs {
		info := model.AppDbBackupInfo{
			Status: strings.ToLower(string(db.Status.Phase)),
		}
		if db.Status.CompletionTimestamp != nil && db.Status.StartTimestamp != nil {
			info.Cost = db.Status.CompletionTimestamp.Sub(db.Status.StartTimestamp.Time).Seconds()
		}
		if db.Status.Size != nil {
			info.Size, _ = db.Status.Size.AsInt64()
		}

		_db, err := i.ks.Store.GetDb(db.Spec.Db.Namespace, db.Spec.Db.Name)
		if err == nil {
			info.DbName = _db.Spec.DbName
			targetNs := _db.Namespace
			if _db.Spec.TargetService.Namespace != "" {
				targetNs = _db.Spec.TargetService.Namespace
			}
			dbsvc, err := i.ks.Store.GetDbService(targetNs, _db.Spec.TargetService.Name)
			if err == nil {
				info.DbType = string(dbsvc.Spec.Type)
			}
		}
		data.DB = append(data.DB, info)
	}

	for _, vol := range volumes {
		info := model.AppPvcBackupInfo{
			PvcName: vol.Labels[constant.LableVeleroPvcUID],
			Volume:  vol.Spec.Volume,
			Status:  strings.ToLower(string(vol.Status.Phase)),
		}

		if vol.Status.CompletionTimestamp != nil && vol.Status.StartTimestamp != nil {
			info.Cost = vol.Status.CompletionTimestamp.Sub(vol.Status.StartTimestamp.Time).Seconds()
		}

		info.TotalBytes = vol.Status.Progress.TotalBytes
		info.DoneBytes = vol.Status.Progress.BytesDone
		data.PVC = append(data.PVC, info)
	}

	return data
}

func (i *Instance) GetBackupStatus(backupName string) (interface{}, error) {
	backup, err := i.ks.Store.GetBackup(constant.DefaultRuntimeNamespace, backupName)
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
			Annotations: map[string]string{},
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

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Restores(constant.DefaultRuntimeNamespace).Create(i.ctx, &restoreReq, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetRestoreStatus(restoreName string) (interface{}, error) {
	restore, err := i.ks.Store.GetRestore(constant.DefaultRuntimeNamespace, restoreName)
	if err != nil {
		return nil, err
	}

	data := map[string]string{
		"status": string(restore.Status.Phase),
		"reason": restore.Status.Reason,
	}

	return data, nil
}
