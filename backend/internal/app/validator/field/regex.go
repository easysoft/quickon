package field

import "regexp"

var (
	versionRegexSemantic = regexp.MustCompile(`^\d+\.\d+\.\d+$`)
)
