package snippet

import (
	"fmt"

	"gopkg.in/yaml.v3"
	v1 "k8s.io/api/core/v1"
)

type Snippet struct {
	cm      *v1.ConfigMap
	content string
	values  map[string]interface{}
}

func NewSnippet(cm *v1.ConfigMap) (*Snippet, error) {
	s := &Snippet{
		cm: cm,
	}
	content, ok := cm.Data[snippetContentKey]
	if !ok {
		return nil, fmt.Errorf("content key '%s' not found", snippetContentKey)
	}
	s.content = content
	err := yaml.Unmarshal([]byte(content), &s.values)
	if err != nil {
		return nil, err
	}
	return s, nil
}

func (s *Snippet) Category() string {
	return s.cm.Annotations[annotationSnippetConfigCategory]
}

func (s *Snippet) IsAutoImport() bool {
	v, ok := s.cm.Annotations[annotationSnippetConfigAutoImport]
	if !ok {
		return false
	}

	if v == "true" {
		return true
	}

	return false
}

func (s *Snippet) Content() string {
	return s.content
}

func (s *Snippet) Values() map[string]interface{} {
	return s.values
}
