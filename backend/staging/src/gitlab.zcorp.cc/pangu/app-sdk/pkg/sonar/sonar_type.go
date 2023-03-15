package sonar

type ResponseSonarToken struct {
	Login string `json:"login,omitempty"`
	Name  string `json:"name"`
	Token string `json:"token,omitempty"`
	Type  string `json:"type"`
}

type ResponseSonarSearchToken struct {
	Login  string               `json:"login"`
	Tokens []ResponseSonarToken `json:"userTokens"`
}
