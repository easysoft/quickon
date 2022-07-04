package app

import (
	"fmt"
	"strings"
	"time"

	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	quchengv1beta1 "gitlab.zcorp.cc/pangu/cne-api/apis/qucheng/v1beta1"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
)

func (i *Instance) CreateBackup() (interface{}, error) {
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
		},
	}

	data := struct {
		BackupName string `json:"backup_name"`
		CreateTime int64  `json:"create_time"`
	}{backupName, currTime.Unix()}

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Backups(i.namespace).Create(i.ctx, &backupReq, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetBackupList() ([]model.AppRespBackup, error) {
	var result []model.AppRespBackup
	backups, err := i.ks.Store.ListBackups(i.namespace, i.selector)
	if err != nil {
		return nil, err
	}

	for _, b := range backups {
		item := model.AppRespBackup{
			Name: b.Name, CreateTime: b.CreationTimestamp.Unix(),
			StorageName: b.Spec.StorageName, Status: strings.ToLower(string(b.Status.Phase)),
			Archives: make([]model.AppRespBackupArchive, 0),
		}

		for _, arch := range b.Status.Archives {
			item.Archives = append(item.Archives, model.AppRespBackupArchive{
				Path: arch.Path,
			})
		}

		result = append(result, item)
	}

	return result, nil
}

func (i *Instance) GetBackupStatus(backupName string) (interface{}, error) {
	backup, err := i.ks.Store.GetBackup(i.namespace, backupName)
	if err != nil {
		return nil, err
	}

	data := map[string]string{
		"status": string(backup.Status.Phase),
		"reason": backup.Status.Reason,
	}

	return data, nil
}

func (i *Instance) CreateRestore(backupName string) (interface{}, error) {
	currTime := time.Now()
	restoreName := fmt.Sprintf("%s-backup-%d", i.name, currTime.Unix())
	restore := quchengv1beta1.Restore{
		ObjectMeta: metav1.ObjectMeta{
			Name: restoreName,
			Labels: map[string]string{
				"app":     i.ChartName,
				"release": i.release.Name,
			},
		},
		Spec: quchengv1beta1.RestoreSpec{
			BackupName: backupName,
		},
	}

	data := struct {
		RestoreName string `json:"restore_name"`
		CreateTime  int64  `json:"create_time"`
	}{backupName, currTime.Unix()}

	_, err := i.ks.Clients.Cne.QuchengV1beta1().Restores(i.namespace).Create(i.ctx, &restore, metav1.CreateOptions{})
	return data, err
}

func (i *Instance) GetRestoreStatus(restoreName string) (interface{}, error) {
	restore, err := i.ks.Store.GetRestore(i.namespace, restoreName)
	if err != nil {
		return nil, err
	}

	data := map[string]string{
		"status": string(restore.Status.Phase),
		"reason": restore.Status.Reason,
	}

	return data, nil
}
