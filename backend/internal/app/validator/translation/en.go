// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package translation

import (
	"fmt"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
	"strings"

	ut "github.com/go-playground/universal-translator"
	"github.com/go-playground/validator/v10"
)

func RegisterCustomENTranslations(v *validator.Validate, trans ut.Translator) (err error) {
	translations := []struct {
		tag             string
		translation     string
		override        bool
		customRegisFunc validator.RegisterTranslationsFunc
		customTransFunc validator.TranslationFunc
	}{
		{
			tag: "namespace_exist",
			customRegisFunc: func(ut ut.Translator) (err error) {

				if err = ut.Add("namespace_exist_string", "{0} {1} not exist", false); err != nil {
					return
				}
				return

			},
			customTransFunc: func(ut ut.Translator, fe validator.FieldError) string {

				var err error
				var t string

				t, err = ut.T("namespace_exist_string", strings.ToLower(fe.Field()), fe.Value().(string))
				if err != nil {
					goto END
				}

			END:
				if err != nil {
					fmt.Printf("warning: error translating FieldError: %s", err)
					return fe.(error).Error()
				}

				return t
			},
		},
		{
			tag: "version_format",
			customRegisFunc: func(ut ut.Translator) (err error) {

				if err = ut.Add("version_invalid_fmt", "{0} {1} is invalid", false); err != nil {
					return
				}
				return

			},
			customTransFunc: func(ut ut.Translator, fe validator.FieldError) string {

				var err error
				var t string

				t, err = ut.T("version_invalid_fmt", strings.ToLower(fe.Field()), fe.Value().(string))
				if err != nil {
					goto END
				}

			END:
				if err != nil {
					fmt.Printf("warning: error translating FieldError: %s", err)
					return fe.(error).Error()
				}

				return t
			},
		},
	}

	for _, t := range translations {

		if t.customTransFunc != nil && t.customRegisFunc != nil {

			err = v.RegisterTranslation(t.tag, trans, t.customRegisFunc, t.customTransFunc)

		} else if t.customTransFunc != nil && t.customRegisFunc == nil {

			err = v.RegisterTranslation(t.tag, trans, registrationFunc(t.tag, t.translation, t.override), t.customTransFunc)

		} else if t.customTransFunc == nil && t.customRegisFunc != nil {

			err = v.RegisterTranslation(t.tag, trans, t.customRegisFunc, translateFunc)

		} else {
			err = v.RegisterTranslation(t.tag, trans, registrationFunc(t.tag, t.translation, t.override), translateFunc)
		}

		if err != nil {
			return
		}
	}
	return
}

func registrationFunc(tag string, translation string, override bool) validator.RegisterTranslationsFunc {

	return func(ut ut.Translator) (err error) {

		if err = ut.Add(tag, translation, override); err != nil {
			return
		}

		return

	}

}

func translateFunc(ut ut.Translator, fe validator.FieldError) string {

	t, err := ut.T(fe.Tag(), fe.Field())
	if err != nil {
		logging.DefaultLogger().Errorf("warning: error translating FieldError: %#v", fe)
		return fe.(error).Error()
	}

	return t
}
