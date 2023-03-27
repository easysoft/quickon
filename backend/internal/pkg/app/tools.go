package app

import (
	"encoding/json"
	"fmt"
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
	if protocol != "http" && protocol != "https" {
		return nil, retcode.UnSupportSchema, fmt.Errorf("unsupport protocol '%s'", protocol)
	}

	if !regHost.MatchString(host) {
		return nil, retcode.InvalidHost, fmt.Errorf("invalid host '%s'", host)
	}

	hostFrames := strings.Split(host, ":")
	s := httplib.HTTPServer{
		Schema:   protocol,
		Host:     hostFrames[0],
		Username: username,
		Password: password,
		Debug:    true,
	}

	if len(hostFrames) > 1 {
		s.Port = hostFrames[1]
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
