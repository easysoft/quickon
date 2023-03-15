package app

import (
	"encoding/json"
	"gitlab.zcorp.cc/pangu/app-sdk/pkg/httplib"
	"net/url"
	"strings"
)

func convertToStruct(input, output interface{}) error {
	bs, err := json.Marshal(input)
	if err != nil {
		return err
	}
	err = json.Unmarshal(bs, output)
	return err
}

func parseHttpServer(host, username, password string) (*httplib.HTTPServer, error) {
	u, err := url.Parse(host)
	if err != nil {
		return nil, err
	}

	hostFrames := strings.Split(u.Host, ":")
	s := httplib.HTTPServer{
		Schema:   u.Scheme,
		Host:     hostFrames[0],
		Username: username,
		Password: password,
		Debug:    true,
	}

	if len(hostFrames) > 1 {
		s.Port = hostFrames[1]
	}
	return &s, nil
}
