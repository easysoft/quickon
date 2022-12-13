// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package router

import (
	"crypto/tls"
	"fmt"
	"github.com/pkg/errors"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/retcode"
	"net"
	"net/http"
	"net/smtp"

	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/service"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	utiltls "gitlab.zcorp.cc/pangu/cne-api/pkg/utils/tls"
)

func SystemUpdate(c *gin.Context) {
	var (
		err  error
		ctx  = c.Request.Context()
		body model.ReqSystemUpdate
	)

	logger := getLogger(ctx).WithField("action", "system-update")
	if err = c.ShouldBindJSON(&body); err != nil {
		logger.WithError(err).Error(errBindDataFailed)
		renderError(c, http.StatusBadRequest, err)
		return
	}

	blankSnippet := make(map[string]interface{})
	runtimeNs := viper.GetString(constant.FlagRuntimeNamespace)

	opApp, err := service.Apps(ctx, "", runtimeNs).GetApp("cne-operator")
	if err != nil {
		logger.WithError(err).Error("get cne-operator app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = opApp.PatchSettings(opApp.ChartName, model.AppCreateOrUpdateModel{
		Version: "latest", Channel: body.Channel,
	}, blankSnippet, nil); err != nil {
		logger.WithError(err).WithField("channel", body.Channel).Info("update operator chart failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	logger.WithField("channel", body.Channel).Info("update operator chart success")

	qcApp, err := service.Apps(ctx, "", runtimeNs).GetApp("qucheng")
	if err != nil {
		logger.WithError(err).Error("get qucheng app failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	if err = qcApp.PatchSettings(qcApp.ChartName, model.AppCreateOrUpdateModel{
		Version: body.Version, Channel: body.Channel,
	}, blankSnippet, nil); err != nil {
		logger.WithError(err).WithField("channel", body.Channel).Errorf("update qucheng chart to version %s failed", body.Version)
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	logger.WithField("channel", body.Channel).Infof("update qucheng chart to version %s success", body.Version)

	renderSuccess(c, http.StatusOK)
}

func FindAllApps(c *gin.Context) {
	var (
		err error
		ctx = c.Request.Context()
	)

	logger := getLogger(ctx)

	data, err := service.Apps(ctx, "", "").ListAllApplications()
	if err != nil {
		logger.WithError(err).Error("list all application failed")
		renderError(c, http.StatusInternalServerError, err)
		return
	}

	renderJson(c, http.StatusOK, data)
}

func AuthMailServer(c *gin.Context) {
	var (
		err  error
		ctx  = c.Request.Context()
		body model.ReqSmtpAuth

		conn       net.Conn
		tlsSupport bool
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if body.Port != "25" {
		tlsSupport = true
	}

	logger := getLogger(ctx)

	addr := fmt.Sprintf("%s:%s", body.Host, body.Port)
	if tlsSupport {
		tlsConfig := tls.Config{ServerName: body.Host}
		conn, err = tls.Dial("tcp", addr, &tlsConfig)
	} else {
		conn, err = net.Dial("tcp", addr)
	}
	if err != nil {
		logger.WithError(err).Errorf("diag connect for %s failed", addr)
		renderError(c, http.StatusBadRequest, err)
		return
	}

	s, err := smtp.NewClient(conn, body.Host)
	if err != nil {
		logger.WithError(err).Errorf("make smtp client for %s failed", addr)
		renderError(c, http.StatusBadRequest, err)
		return
	}
	defer s.Close()
	if err = s.Hello("localhost"); err != nil {
		logger.WithError(err).Errorf("say helo failed")
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if ok, _ := s.Extension("STARTTLS"); ok && !tlsSupport {
		config := &tls.Config{ServerName: body.Host}
		if err = s.StartTLS(config); err != nil {
			logger.WithError(err).Errorf("starttls failed")
			renderError(c, http.StatusBadRequest, err)
			return
		}
	}

	if ok, _ := s.Extension("AUTH"); ok {
		authInfo := smtp.PlainAuth("", body.User, body.Pass, body.Host)
		if err = s.Auth(authInfo); err != nil {
			logger.WithError(err).Errorf("auth for user %s failed", body.User)
			renderError(c, http.StatusBadRequest, err)
			return
		}
	}

	renderSuccess(c, http.StatusOK)
}

func UploadTLS(c *gin.Context) {
	var (
		err  error
		ctx  = c.Request.Context()
		body model.ReqTLSUpload
	)

	if err = c.ShouldBindJSON(&body); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	logger := getLogger(ctx)
	logger.Debugf("certificate: %s", body.CertificatePem)
	logger.Debugf("privateKey: %s", body.PrivateKeyPem)

	t, err := utiltls.Parse([]byte(body.CertificatePem), []byte(body.PrivateKeyPem))
	if err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	if err = t.Valid(); err != nil {
		renderError(c, translateError(err), err)
		return
	}

	err = service.Apps(ctx, "", viper.GetString(constant.FlagRuntimeNamespace)).UploadTLS(body.Name, body.CertificatePem, body.PrivateKeyPem)
	if err != nil {
		renderError(c, http.StatusInternalServerError, err)
		return
	}
	renderJson(c, http.StatusOK, t.GetCertInfo())

}

func ReadTLSInfo(c *gin.Context) {
	var (
		err   error
		ctx   = c.Request.Context()
		query struct {
			model.QueryCluster
			Name string `form:"name"`
		}
	)

	if err = c.ShouldBindQuery(&query); err != nil {
		renderError(c, http.StatusBadRequest, err)
		return
	}

	info, err := service.Apps(ctx, "", viper.GetString(constant.FlagRuntimeNamespace)).ReadTLSCertInfo(query.Name)
	if err != nil {
		renderError(c, translateError(err), err)
		return
	}
	renderJson(c, http.StatusOK, info)
}

func translateError(e error) int {
	var code = retcode.DefaultCode
	if errors.Is(e, utiltls.ErrUnmatchedCertificate) {
		code = retcode.UnmatchedCertificate
	} else if errors.Is(e, utiltls.ErrExpiredCertificate) {
		code = retcode.ExpiredCertificate
	} else if errors.Is(e, utiltls.ErrIncompleteCertificateChain) {
		code = retcode.IncompleteCertificateChain
	}

	return int(code)
}
