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

const (
	BackupPhaseNew           BackupPhase = "New"
	BackupPhaseProcess       BackupPhase = "Processing"
	BackupPhaseExecuteFailed BackupPhase = "ExecuteFailed"

	BackupPhaseUploading     BackupPhase = "Uploading"
	BackupPhaseUploadFailure BackupPhase = "UploadFailed"
	BackupPhaseCompleted     BackupPhase = "Completed"
)

const (
	RestorePhaseNew           RestorePhase = "New"
	RestorePhaseProcess       RestorePhase = "Processing"
	RestorePhaseExecuteFailed RestorePhase = "ExecuteFailed"

	RestorePhaseDownloading   RestorePhase = "Downloading"
	RestorePhaseUploadFailure RestorePhase = "DownloadFailed"
	RestorePhaseCompleted     RestorePhase = "Completed"
)
