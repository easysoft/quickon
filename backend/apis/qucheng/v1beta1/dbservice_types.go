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
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
)

type DbType string

const (
	DbTypeMysql = "mysql"
)

// DbServiceSpec defines the desired state of DbService
type DbServiceSpec struct {
	Type    DbType  `json:"type"`
	Service Service `json:"service,omitempty"`
	Account Account `json:"account"`
}

// DbServiceStatus defines the observed state of DbService
type DbServiceStatus struct {
	// INSERT ADDITIONAL STATUS FIELD - define observed state of cluster
	// Important: Run "make" to regenerate code after modifying this file
}

//+genclient
//+kubebuilder:object:root=true
//+kubebuilder:subresource:status

// DbService is the Schema for the dbservices API
type DbService struct {
	metav1.TypeMeta   `json:",inline"`
	metav1.ObjectMeta `json:"metadata,omitempty"`

	Spec   DbServiceSpec   `json:"spec,omitempty"`
	Status DbServiceStatus `json:"status,omitempty"`
}

//+kubebuilder:object:root=true

// DbServiceList contains a list of DbService
type DbServiceList struct {
	metav1.TypeMeta `json:",inline"`
	metav1.ListMeta `json:"metadata,omitempty"`
	Items           []DbService `json:"items"`
}

func init() {
	SchemeBuilder.Register(&DbService{}, &DbServiceList{})
}
