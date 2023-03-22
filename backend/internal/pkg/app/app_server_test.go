package app

import (
	"testing"

	"github.com/stretchr/testify/assert"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/retcode"
)

func TestHost(t *testing.T) {
	type testServerinfo struct {
		ServerInfo
		ExpectErr  bool
		ExpectCode retcode.RetCode
	}

	mockData := []testServerinfo{
		{ServerInfo: ServerInfo{Protocol: "http", Host: "abc.com"}, ExpectErr: false, ExpectCode: retcode.OK},
		{ServerInfo: ServerInfo{Protocol: "http", Host: "http://abc.com"}, ExpectErr: true, ExpectCode: retcode.InvalidHost},
		{ServerInfo: ServerInfo{Protocol: "ftp", Host: "https://abc.com"}, ExpectErr: true, ExpectCode: retcode.UnSupportSchema},
		{ServerInfo: ServerInfo{Protocol: "https", Host: "abc.com:8080"}, ExpectErr: false, ExpectCode: retcode.OK},
		{ServerInfo: ServerInfo{Protocol: "https", Host: "ftp://abc.com"}, ExpectErr: true, ExpectCode: retcode.InvalidHost},
	}

	for _, s := range mockData {
		_, code, err := parseHttpServer(s.Protocol, s.Host, "", "")
		if !s.ExpectErr {
			assert.NoError(t, err)
		} else {
			assert.Error(t, err)
		}

		assert.Equal(t, s.ExpectCode, code)
	}
}
