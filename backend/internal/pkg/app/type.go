package app

type ServerInfo struct {
	Protocol string `json:"protocol"`
	Host     string `json:"host"`
	Username string `json:"username,omitempty"`
	Password string `json:"password,omitempty"`
}
