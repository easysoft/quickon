package license

var validators = []Validator{newDefaultValidator()}

type Validator interface {
	Validate() bool
}

type defaultValidator struct {
}

func newDefaultValidator() Validator {
	return &defaultValidator{}
}

func (d *defaultValidator) Validate() bool {
	return false
}

func AddValidator(v Validator) {
	validators = append(validators, v)
}

func IsValid() bool {
	count := len(validators)
	if count == 0 {
		return false
	}
	v := validators[count-1]
	return v.Validate()
}
