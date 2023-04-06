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
	var h string

	if strings.HasPrefix(host, "http") {
		h = host
	} else {
		// Match invalid host like
		if !regHost.MatchString(host) {
			return nil, retcode.InvalidHost, fmt.Errorf("invalid host %s", host)
		}

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

var regHost = regexp.MustCompile(`^[^/?#]*$`)

func validHttpServer(s *httplib.HTTPServer) (retcode.RetCode, error) {
	if s.Schema != "http" && s.Schema != "https" {
		return retcode.UnSupportSchema, fmt.Errorf("unsupport schema '%s'", s.Schema)
	}

	fmt.Printf("host: %+v", s)
	if !regHost.MatchString(s.Host) {
		return retcode.InvalidHost, fmt.Errorf("invalid host '%s'", s.Host)
	}
	fmt.Println(110)

	return retcode.OK, nil
}
