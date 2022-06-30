package v1beta1

import "k8s.io/apimachinery/pkg/runtime/schema"

func Resource(resource string) schema.GroupResource {
	return GroupVersion.WithResource(resource).GroupResource()
}
