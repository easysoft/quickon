// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"bytes"
	"compress/gzip"
	"encoding/base64"
	"encoding/json"
	"io"

	"helm.sh/helm/v3/pkg/release"
	"helm.sh/helm/v3/pkg/releaseutil"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/store"
)

type ReleaseGetter struct {
	namespace string
	store     *store.Storer
}

func (r *ReleaseGetter) History(name string) ([]*release.Release, error) {
	lbs := labels.Set{"owner": "helm", "name": name}.AsSelector()
	secrets, err := r.store.ListSecrets(r.namespace, lbs)
	if err != nil {
		return nil, err
	}

	var result []*release.Release
	for _, item := range secrets {
		rls, err := decodeRelease(string(item.Data["release"]))
		if err != nil {
			continue
		}
		result = append(result, rls)
	}

	return result, nil
}

func (r *ReleaseGetter) Last(name string) (*release.Release, error) {
	revisions, err := r.History(name)
	if err != nil {
		return nil, err
	}

	if len(revisions) == 0 {
		return nil, err
	}

	releaseutil.Reverse(revisions, releaseutil.SortByRevision)
	return revisions[0], nil
}

var b64 = base64.StdEncoding

var magicGzip = []byte{0x1f, 0x8b, 0x08}

// decodeRelease decodes the bytes of data into a release
// type. Data must contain a base64 encoded gzipped string of a
// valid release, otherwise an error is returned.
func decodeRelease(data string) (*release.Release, error) {
	// base64 decode string
	b, err := b64.DecodeString(data)
	if err != nil {
		return nil, err
	}

	// For backwards compatibility with releases that were stored before
	// compression was introduced we skip decompression if the
	// gzip magic header is not found
	if bytes.Equal(b[0:3], magicGzip) {
		r, err := gzip.NewReader(bytes.NewReader(b))
		if err != nil {
			return nil, err
		}
		defer r.Close()
		b2, err := io.ReadAll(r)
		if err != nil {
			return nil, err
		}
		b = b2
	}

	var rls release.Release
	// unmarshal release object bytes
	if err := json.Unmarshal(b, &rls); err != nil {
		return nil, err
	}
	return &rls, nil
}
