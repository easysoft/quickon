package parse

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"gopkg.in/yaml.v3"
)

func TestParse(t *testing.T) {
	var demoContent = []byte(`---
auth:
  ldap:
    attrEmail: mail
    attrUser: uid
    baseDN: dc=quickon,dc=org
    bindDN: cn=admin,dc=quickon,dc=org
    bindPass: ldapPassw0rd
    enabled: true
    filter: '&(objectClass=posixAccount)(uid=%s)'
    host: 10.43.97.26
    port: "1389"
    testThread: 0
    type: ldap
global:
  ingress:
    enabled: false
    host: yfmd.qisy-test.cn
ingress:
  enabled: true
  host: yfmd.qisy-test.cn
mail:
  enabled: true
  smtp:
    host: cne-courier-qadmin-20221121150544.cne-system.svc
    pass: LixX3yzhPv7NAWdIQlJU9rDb
    port: 1025
    user: t@yunop.com
`)
	var obj map[string]interface{}
	err := yaml.Unmarshal(demoContent, &obj)
	if err != nil {
		t.Error(err)
	}

	p := New(obj)

	testString := []struct {
		path   string
		expect string
		find   bool
	}{
		{"ingress.host", "yfmd.qisy-test.cn", true},
		{"mail.host", "", false},
		{"mail.smtp.user", "t@yunop.com", true},
		{"mail.imap.user", "", false},
	}

	for _, tc := range testString {
		v, ok := p.GetString(tc.path)
		assert.Equal(t, tc.expect, v, "path %s, expect %s", tc.path, tc.expect)
		assert.Equal(t, tc.find, ok, "path %s, expect %v", tc.path, tc.find)
	}

	testBool := []struct {
		path   string
		expect bool
		find   bool
	}{
		{"ingress.enabled", true, true},
		{"global.ingress.enabled", false, true},
		{"ingress.xxd", false, false},
	}
	for _, tc := range testBool {
		v, ok := p.GetBool(tc.path)
		assert.Equal(t, tc.expect, v, "path %s, expect %v", tc.path, tc.expect)
		assert.Equal(t, tc.find, ok, "path %s, expect %v", tc.path, tc.find)
	}

	testInt := []struct {
		path   string
		expect int
		find   bool
	}{
		{"auth.ldap.testThread", 0, true},
		{"mail.smtp.port", 1025, true},
		{"mail.imap.port", 0, false},
	}
	for _, tc := range testInt {
		v, ok := p.GetInt(tc.path)
		assert.Equal(t, tc.expect, v, "path %s, expect %d", tc.path, tc.expect)
		assert.Equal(t, tc.find, ok, "path %s, expect %v", tc.path, tc.find)
	}
}
