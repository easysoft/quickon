package validator

import (
	"fmt"

	"github.com/gin-gonic/gin/binding"
	"github.com/go-playground/locales/en"
	ut "github.com/go-playground/universal-translator"
	"github.com/go-playground/validator/v10"
	enTranslations "github.com/go-playground/validator/v10/translations/en"
	"k8s.io/klog/v2"

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
	if v, ok := binding.Validator.Engine().(*validator.Validate); ok {
		var err error
		for k, f := range field.New() {
			if err = v.RegisterValidation(k, f); err != nil {
				klog.ErrorS(err, "register validation failed", "name", k)
			} else {
				klog.InfoS("register validation successful", "name", k)
			}
		}

		if err = initTrans(v); err != nil {
			klog.ErrorS(err, "setup translate failed")
		}
	}
}

func LoadTrans() ut.Translator {
	return trans
}
