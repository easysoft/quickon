// Copyright (c) 2022-2022 北京渠成软件有限公司(Beijing Qucheng Software Co., Ltd. www.qucheng.com) All rights reserved.
// Use of this source code is covered by the following dual licenses:
// (1) Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// (2) Affero General Public License 3.0 (AGPL 3.0)
// license that can be found in the LICENSE file.

package v1beta1

import (
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/util/intstr"
)

type Service struct {
	Name      string             `json:"name,omitempty"`
	Namespace string             `json:"namespace,omitempty"`
	Port      intstr.IntOrString `json:"port,omitempty"`
}

type Account struct {
	User     AccountUser     `json:"user"`
	Password AccountPassword `json:"password"`
}

type AccountUser struct {
	Value     string       `json:"value,omitempty"`
	ValueFrom *ValueSource `json:"valueFrom,omitempty"`
}

type AccountPassword struct {
	Value     string       `json:"value,omitempty"`
	ValueFrom *ValueSource `json:"valueFrom,omitempty"`
}

type ValueSource struct {
	// Selects a key of a ConfigMap.
	// +optional
	ConfigMapKeyRef *v1.ConfigMapKeySelector `json:"configMapKeyRef,omitempty" protobuf:"bytes,3,opt,name=configMapKeyRef"`
	// Selects a key of a secret in the pod's namespace
	// +optional
	SecretKeyRef *v1.SecretKeySelector `json:"secretKeyRef,omitempty" protobuf:"bytes,4,opt,name=secretKeyRef"`
}

type BackupPhase string
type RestorePhase string
type GlobalDBPhase string

const (
	BackupPhaseNew           BackupPhase = "New"
	BackupPhaseProcess       BackupPhase = "Processing"
	BackupPhaseFailed        BackupPhase = "Failed"
	BackupPhaseExecuteFailed BackupPhase = "ExecuteFailed"

	BackupPhaseUploading     BackupPhase = "Uploading"
	BackupPhaseUploadFailure BackupPhase = "UploadFailed"
	BackupPhaseCompleted     BackupPhase = "Completed"
)

const (
	RestorePhaseNew           RestorePhase = "New"
	RestorePhaseProcess       RestorePhase = "Processing"
	RestorePhaseFailed        RestorePhase = "Failed"
	RestorePhaseExecuteFailed RestorePhase = "ExecuteFailed"

	RestorePhaseDownloading     RestorePhase = "Downloading"
	RestorePhaseDownloadFailure RestorePhase = "DownloadFailed"
	RestorePhaseCompleted       RestorePhase = "Completed"
)

const (
	GlobalDBStateNew   GlobalDBPhase = "new"
	GlobalDBStateReady GlobalDBPhase = "ready"
)
