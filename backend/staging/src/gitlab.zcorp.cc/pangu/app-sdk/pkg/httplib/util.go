// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package httplib

import (
	"bytes"
	"net/url"
)

type BasicAuth struct {
	Username string
	Password string
}

// GenerateHTTPServer return the full http host
func GenerateHTTPServer(server *HTTPServer, basicAuth *BasicAuth) string {
	var httpserver bytes.Buffer
	httpserver.WriteString(server.Schema)
	httpserver.WriteString("://")
	if basicAuth != nil {
		httpserver.WriteString(basicAuth.Username)
		httpserver.WriteString(":")
		httpserver.WriteString(basicAuth.Password)
		httpserver.WriteString("@")
	}
	httpserver.WriteString(server.Host)
	if (server.Schema == "http" && server.Port != "" && server.Port != "80") ||
		(server.Schema == "https" && server.Port != "" && server.Port != "443") {
		httpserver.WriteString(":" + server.Port)
	}
	return httpserver.String()
}

// generateURL return the full http request url
// with query parameters encode
func generateURL(server *HTTPServer, basicAuth *BasicAuth, path string, queries ...[2]string) string {
	var uri bytes.Buffer
	uri.WriteString(GenerateHTTPServer(server, basicAuth))
	if path != "" {
		uri.WriteString(path)
	}

	if len(queries) > 0 {
		uri.WriteString("?")
		v := url.Values{}
		for _, item := range queries {
			v.Add(item[0], item[1])
		}
		uri.WriteString(v.Encode())
	}

	return uri.String()
}

func GenerateURL(server *HTTPServer, path string, queries ...[2]string) string {
	return generateURL(server, nil, path, queries...)
}

func GenerateURLWithBasicAuth(server *HTTPServer, baseAuth *BasicAuth, path string, queries ...[2]string) string {
	return generateURL(server, baseAuth, path, queries...)
}
