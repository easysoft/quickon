package model

type SnippetConfig struct {
	Name       string                 `json:"name"`
	Namespace  string                 `json:"namespace"`
	Category   string                 `json:"category"`
	Values     map[string]interface{} `json:"values"`
	AutoImport bool                   `json:"auto_import,omitempty"`
}

type SnippetQuery struct {
	Name      string `form:"name" binding:"required"`
	Namespace string `form:"namespace"`
}
