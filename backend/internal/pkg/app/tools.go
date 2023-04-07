package app

import (
	"encoding/json"
	"fmt"
	"net/url"
	"regexp"
	"strings"

	"gitlab.zcorp.cc/pangu/app-sdk/pkg/httplib"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/retcode"
)

func convertToStruct(input, output interface{}) error {
	bs, err := json.Marshal(input)
	if err != nil {
		return err
	}
	err = json.Unmarshal(bs, output)
	return err
}

func parseModel(data interface{}) (*ServerInfo, error) {
	var m ServerInfo
	err := convertToStruct(data, &m)
	if err != nil {
		return nil, err
	}
	return &m, nil
}

func parseHttpServer(protocol, host, username, password string) (*httplib.HTTPServer, retcode.RetCode, error) {
	// Match invalid host like
	if !regHost.MatchString(host) {
		return nil, retcode.InvalidHost, fmt.Errorf("invalid host %s", host)
	}

	var h string

	if strings.HasPrefix(host, "http") {
		h = host
	} else {
		if protocol == "" {
			h = "https" + "://" + host
		} else {
			h = protocol + "://" + host
		}
	}

	u, err := url.Parse(h)
	if err != nil {
		return nil, retcode.InvalidHost, err
	}
	s := httplib.HTTPServer{
		Schema:   u.Scheme,
		Host:     u.Hostname(),
		Port:     u.Port(),
		Username: username,
		Password: password,
		Debug:    true,
	}

	return &s, retcode.OK, nil
}

var regHost = regexp.MustCompile(`^(:?https?://)?[^/?#]*$`)
