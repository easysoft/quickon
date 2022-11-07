// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package job

import (
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

func LoadJob() {
	logging.DefaultLogger().WithField("job", "load").Info("load internal job")
	if err := CheckReNewCertificate(); err != nil {
		logging.DefaultLogger().WithField("job", "renewtls").Errorf("check renew certificate failed: %v", err)
	}
	if err := CheckOSSAlive(); err != nil {
		logging.DefaultLogger().WithField("job", "checkossalive").Errorf("check oss alive failed: %v", err)
	}
}
