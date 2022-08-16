package model

type SnippetConfig struct {
	Name       string `json:"name"`
	Namespace  string `json:"namespace"`
	Category   string `json:"category,omitempty"`
	Content    string `json:"content"`
	AutoImport bool   `json:"auto-import,omitempty"`
}
