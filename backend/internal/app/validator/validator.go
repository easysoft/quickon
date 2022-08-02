// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package validator

import (
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"

	"github.com/gin-gonic/gin/binding"
	"github.com/go-playground/locales/en"
	ut "github.com/go-playground/universal-translator"
	"github.com/go-playground/validator/v10"
	enTranslations "github.com/go-playground/validator/v10/translations/en"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/validator/field"
	"gitlab.zcorp.cc/pangu/cne-api/internal/app/validator/translation"
)

var trans ut.Translator

func initTrans(v *validator.Validate) (err error) {
	enT := en.New()
	uni := ut.New(enT, enT)

	var (
		ok    bool
		local = "en"
	)

	trans, ok = uni.GetTranslator(local)
	if !ok {
		return fmt.Errorf("uni.GetTranslator(%s) failed", local)
	}

	if err = enTranslations.RegisterDefaultTranslations(v, trans); err != nil {
		return
	}
	if err = translation.RegisterCustomENTranslations(v, trans); err != nil {
		return
	}
	return
}

func Setup() {
	logger := logging.DefaultLogger()
	if v, ok := binding.Validator.Engine().(*validator.Validate); ok {
		var err error
		for k, f := range field.New() {
			if err = v.RegisterValidation(k, f); err != nil {
				logger.WithError(err).WithField("name", k).Error("register validation failed")
			} else {
				logger.WithField("name", k).Info("register validation successful")
			}
		}

		if err = initTrans(v); err != nil {
			logger.WithError(err).Error("setup translate failed")
		}
	}
}

func LoadTrans() ut.Translator {
	return trans
}
