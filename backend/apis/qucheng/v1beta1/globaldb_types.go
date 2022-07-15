// Copyright (c) 2022-2022 北京渠成软件有限公司(Beijing Qucheng Software Co., Ltd. www.qucheng.com) All rights reserved.
// Use of this source code is covered by the following dual licenses:
// (1) Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// (2) Affero General Public License 3.0 (AGPL 3.0)
// license that can be found in the LICENSE file.

package v1beta1

import (
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
)

// EDIT THIS FILE!  THIS IS SCAFFOLDING FOR YOU TO OWN!
// NOTE: json tags are required.  Any new fields you add must have json tags for the fields to be serialized.

// GlobalDBSpec defines the desired state of GlobalDB
type GlobalDBSpec struct {
	// INSERT ADDITIONAL SPEC FIELDS - desired state of cluster
	// Important: Run "make" to regenerate code after modifying this file
	State   State  `json:"state" yaml:"state"`
	Source  Source `json:"source,omitempty" yaml:"source,omitempty"`
	Type    DbType `json:"type" yaml:"type"`
	Version string `json:"version,omitempty" yaml:"version,omitempty"`
}

type Source struct {
	Host string `json:"host,omitempty" yaml:"host,omitempty"`
	Port int    `json:"port,omitempty" yaml:"port,omitempty"`
	User string `json:"user,omitempty" yaml:"user,omitempty"`
	Pass string `json:"pass,omitempty" yaml:"pass,omitempty"`
}

// +kubebuilder:validation:Enum=new;exist
type State string

// GlobalDBStatus defines the observed state of GlobalDB
type GlobalDBStatus struct {
	// INSERT ADDITIONAL STATUS FIELD - define observed state of cluster
	// Important: Run "make" to regenerate code after modifying this file
	Address string `json:"address,omitempty" yaml:"address,omitempty"`
	Network bool   `json:"network" yaml:"network"`
	Auth    bool   `json:"auth" yaml:"auth"`
	Ready   bool   `json:"ready" yaml:"ready"`
	ChildDB int64  `json:"child,omitempty" yaml:"child,omitempty"`
}

//+genclient
//+kubebuilder:object:root=true
//+kubebuilder:subresource:status
//+kubebuilder:printcolumn:name="Network",type=boolean,JSONPath=`.status.network`
//+kubebuilder:printcolumn:name="Auth",type=boolean,JSONPath=`.status.auth`
//+kubebuilder:printcolumn:name="Ready",type=boolean,JSONPath=`.status.ready`
//+kubebuilder:printcolumn:name="Address",type=string,JSONPath=`.status.address`
//+kubebuilder:printcolumn:name="State",type=string,JSONPath=`.spec.state`
//+kubebuilder:printcolumn:name="Version",type=string,JSONPath=`.spec.version`
//+kubebuilder:printcolumn:name="Age",type="date",JSONPath=".metadata.creationTimestamp"
//+kubebuilder:resource:path=globaldbs,shortName=gdb

// GlobalDB is the Schema for the globaldbs API
type GlobalDB struct {
	metav1.TypeMeta   `json:",inline"`
	metav1.ObjectMeta `json:"metadata,omitempty"`

	Spec   GlobalDBSpec   `json:"spec,omitempty"`
	Status GlobalDBStatus `json:"status,omitempty"`
}

//+kubebuilder:object:root=true

// GlobalDBList contains a list of GlobalDB
type GlobalDBList struct {
	metav1.TypeMeta `json:",inline"`
	metav1.ListMeta `json:"metadata,omitempty"`
	Items           []GlobalDB `json:"items"`
}

func init() {
	SchemeBuilder.Register(&GlobalDB{}, &GlobalDBList{})
}
