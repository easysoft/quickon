package gitea

type RequestUserCreate struct {
	Email              string `json:"email"`
	Username           string `json:"username"`
	Password           string `json:"password"`
	MustChangePassword bool   `json:"must_change_password"`
}

type ResponseUserInfo struct {
	Id       int64  `json:"id"`
	Username string `json:"username"`
}

type RequestTokenCreate struct {
	Name string `json:"name"`
}

type ResponseTokenInfo struct {
	Id             int64  `json:"id"`
	Name           string `json:"name"`
	Sha1           string `json:"sha1"`
	TokenLastEight string `json:"token_last_eight"`
}

// Organizations

type RequestOrgCreate struct {
	Username                  string `json:"username"`
	Visibility                string `json:"visibility"`
	RepoAdminChangeTeamAccess bool   `json:"repo_admin_change_team_access"`
}

type ResponseOrgInfo struct {
	Id       int64  `json:"id"`
	Username string `json:"username"`
}

type RequestTeamCreate struct {
	Name                    string `json:"name"`
	CanCreateOrgRepo        bool   `json:"can_create_org_repo"`
	Description             string `json:"description"`
	IncludesAllRepositories bool   `json:"includes_all_repositories"`
	Permission              string `json:"permission"`
}

type ResponseTeamInfo struct {
	Id   int64  `json:"id"`
	Name string `json:"name"`
}

type RequestRepoCreate struct {
	Name    string `json:"name"`
	Private bool   `json:"private"`
}

type ResponseRepoInfo struct {
	Id   int64  `json:"id"`
	Name string `json:"name"`
}
