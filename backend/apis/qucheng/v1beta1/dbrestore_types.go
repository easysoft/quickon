/*
Copyright 2022.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

package v1beta1

import (
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
)

// EDIT THIS FILE!  THIS IS SCAFFOLDING FOR YOU TO OWN!
// NOTE: json tags are required.  Any new fields you add must have json tags for the fields to be serialized.

// DbRestoreSpec defines the desired state of DbRestore
type DbRestoreSpec struct {
	Db   v1.ObjectReference `json:"db"`
	Path string             `json:"path"`
}

type DbRestorePhase string

const (
	DbRestorePhaseNew        DbRestorePhase = "New"
	DbRestorePhaseInProgress DbRestorePhase = "InProgress"
	DbRestorePhaseCompleted  DbRestorePhase = "Completed"
	DbRestorePhaseFailed     DbRestorePhase = "Failed"
)

// DbRestoreStatus defines the observed state of DbRestore
type DbRestoreStatus struct {
	Phase               DbRestorePhase `json:"phase"`
	Message             string         `json:"message,omitempty"`
	StartTimestamp      *metav1.Time   `json:"startTimestamp,omitempty"`
	CompletionTimestamp *metav1.Time   `json:"completionTimestamp,omitempty"`
}

//+genclient
//+kubebuilder:object:root=true
//+kubebuilder:subresource:status

// DbRestore is the Schema for the dbrestores API
type DbRestore struct {
	metav1.TypeMeta   `json:",inline"`
	metav1.ObjectMeta `json:"metadata,omitempty"`

	Spec   DbRestoreSpec   `json:"spec,omitempty"`
	Status DbRestoreStatus `json:"status,omitempty"`
}

//+kubebuilder:object:root=true

// DbRestoreList contains a list of DbRestore
type DbRestoreList struct {
	metav1.TypeMeta `json:",inline"`
	metav1.ListMeta `json:"metadata,omitempty"`
	Items           []DbRestore `json:"items"`
}

func init() {
	SchemeBuilder.Register(&DbRestore{}, &DbRestoreList{})
}
