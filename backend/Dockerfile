# Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
# Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
# license that can be found in the LICENSE file.

FROM hub.qucheng.com/library/god AS builder

WORKDIR /go/src

ENV GOPROXY=https://goproxy.cn,direct

COPY go.mod go.mod

COPY go.sum go.sum

RUN go mod download

COPY . .

RUN make build

FROM hub.qucheng.com/library/debian

COPY --from=builder /go/src/_output/bin/cne-api /usr/bin/cne-api

RUN chmod +x /usr/bin/cne-api

CMD ["/usr/bin/cne-api", "serve"]
