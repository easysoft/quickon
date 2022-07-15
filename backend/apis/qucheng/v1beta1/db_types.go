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

// EDIT THIS FILE!  THIS IS SCAFFOLDING FOR YOU TO OWN!
// NOTE: json tags are required.  Any new fields you add must have json tags for the fields to be serialized.

// DbSpec defines the desired state of Db
type DbSpec struct {
	DbName        string  `json:"dbName,omitempty"`
	TargetService Service `json:"targetService"`
	Account       Account `json:"account,omitempty"`
}

// DbStatus defines the observed state of Db
type DbStatus struct {
	// INSERT ADDITIONAL STATUS FIELD - define observed state of cluster
	// Important: Run "make" to regenerate code after modifying this file
	Address string `json:"address,omitempty" yaml:"address,omitempty"`
	Network bool   `json:"network" yaml:"network"`
	Auth    bool   `json:"auth" yaml:"auth"`
	Ready   bool   `json:"ready" yaml:"ready"`
}

//+genclient
//+kubebuilder:object:root=true
//+kubebuilder:subresource:status
//+kubebuilder:printcolumn:name="Network",type=boolean,JSONPath=`.status.network`
//+kubebuilder:printcolumn:name="Auth",type=boolean,JSONPath=`.status.auth`
//+kubebuilder:printcolumn:name="Ready",type=boolean,JSONPath=`.status.ready`
//+kubebuilder:printcolumn:name="Address",type=string,JSONPath=`.status.address`
//+kubebuilder:printcolumn:name="Age",type="date",JSONPath=".metadata.creationTimestamp"

// Db is the Schema for the dbs API
type Db struct {
	metav1.TypeMeta   `json:",inline"`
	metav1.ObjectMeta `json:"metadata,omitempty"`

	Spec   DbSpec   `json:"spec,omitempty"`
	Status DbStatus `json:"status,omitempty"`
}

//+kubebuilder:object:root=true

// DbList contains a list of Db
type DbList struct {
	metav1.TypeMeta `json:",inline"`
	metav1.ListMeta `json:"metadata,omitempty"`
	Items           []Db `json:"items"`
}

func init() {
	SchemeBuilder.Register(&Db{}, &DbList{})
}
