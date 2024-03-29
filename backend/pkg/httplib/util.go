// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package httplib

import (
	"bytes"
	"net/url"
)

// GenerateHTTPServer return the full http host
func GenerateHTTPServer(server *HTTPServer) string {
	var httpserver bytes.Buffer
	httpserver.WriteString(server.Schema)
	httpserver.WriteString("://")
	httpserver.WriteString(server.Host)
	if (server.Schema == "http" && server.Port != "80") || (server.Schema == "https" && server.Port != "443") {
		httpserver.WriteString(":" + server.Port)
	}
	return httpserver.String()
}

// GenerateURL return the full http request url
// with query parameters encode
func GenerateURL(server *HTTPServer, path string, queries ...[2]string) string {
	var uri bytes.Buffer
	uri.WriteString(GenerateHTTPServer(server))
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
