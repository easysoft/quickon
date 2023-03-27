package middleware

import (
	"io/ioutil"
	"os"
	"testing"

	"github.com/stretchr/testify/assert"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
)

func TestReadConfig(t *testing.T) {
	dir, err := os.MkdirTemp("/tmp", "qucheng-test-*")
	assert.NoError(t, err)
	defer os.RemoveAll(dir)

	testContent := []byte(`
mysql-0:
  host: 192.168.100.1
  port: 3306
  user: root
  password: qq123456
mysql-1:
  host: 172.17.9.11
  port: 3307
  user: admin
  password: hqwueyqsu
mysql-2:
  host: 10.2.9.8
  port: 3306
  user: root
  password: 8jfug76aa
`)
	dbsCfgfile := dir + "/" + constant.DbConfigFile
	err = ioutil.WriteFile(dbsCfgfile, testContent, 0644)
	assert.NoError(t, err)

	_, err = loadConfig()
	assert.ErrorIs(t, err, os.ErrNotExist)

	_ = os.Setenv(constant.ENV_CONFIG_DIR, dir)
	dbs, err := loadConfig()
	assert.NoError(t, err)

	count := len(dbs)
	assert.EqualValues(t, 3, count)

	dsn1, obj1, err := readDSN("mysql-0")
	assert.NoError(t, err)
	assert.Equal(t, "192.168.100.1", obj1.Host)
	assert.Equal(t, 3306, obj1.Port)
	assert.Contains(t, dsn1, "root:qq123456@tcp(192.168.100.1:3306)")

	dsn2, obj2, err := readDSN("mysql1")
	assert.EqualError(t, err, ErrInstanceNotFound.Error())
	assert.Nil(t, obj2)
	assert.Empty(t, dsn2)

	_ = os.Setenv("CLOUD_MYSQL_HOST", "1.2.3.4")
	_ = os.Setenv("CLOUD_MYSQL_PORT", "3309")
	_ = os.Setenv("CLOUD_MYSQL_USER", "root")
	_ = os.Setenv("CLOUD_MYSQL_PASSWORD", "pwd123")

	dsn3, obj3, err := readDSN("")
	assert.NoError(t, err)
	assert.Equal(t, "1.2.3.4", obj3.Host)
	assert.Contains(t, dsn3, "root:pwd123@tcp(1.2.3.4:3309)")

}
