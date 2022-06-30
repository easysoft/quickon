#!/usr/bin/env bash

go mod tidy
go mod vendor
retVal=$?
if [ $retVal -ne 0 ]; then
    exit $retVal
fi

set -e
TMP_DIR=$(mktemp -d)
mkdir -p "${TMP_DIR}"/src/gitlab.zcorp.cc/pangu/cne-api
cp -r ./{apis,hack,vendor,go.mod} "${TMP_DIR}"/src/gitlab.zcorp.cc/pangu/cne-api

(cd "${TMP_DIR}"/src/gitlab.zcorp.cc/pangu/cne-api; \
    GOPATH=${TMP_DIR} GO111MODULE=off /bin/bash vendor/k8s.io/code-generator/generate-groups.sh all \
    gitlab.zcorp.cc/pangu/cne-api/pkg/client/cne gitlab.zcorp.cc/pangu/cne-api/apis "qucheng:v1beta1" -h ./hack/boilerplate.go.txt ;
    )

rm -rf ./pkg/client/cne/{clientset,informers,listers}
mv "${TMP_DIR}"/src/gitlab.zcorp.cc/pangu/cne-api/pkg/client/cne/* ./pkg/client/cne
rm -rf ${TMP_DIR}
