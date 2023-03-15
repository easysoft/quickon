package retcode

type Error struct {
	err  error
	code RetCode
}

func NewError(e error, c int) *Error {
	return &Error{
		err: e, code: RetCode(c),
	}
}

func (e *Error) Error() string {
	return e.Error()
}

type RetCode int

const DefaultCode RetCode = 40000

// Define app errors

// Define system errors

const (
	ExpiredCertificate         RetCode = iota + 41001 // 证书过期
	UnmatchedCertificate                              // 证书不匹配
	IncompleteCertificateChain                        // 证书链不完整
	InvalidCertKeyPair                                // 私钥与证书不匹配
	ParseCertificate                                  // 证书解析失败
	ParsePrivateKey                                   // 密钥解析失败
)

// Define solution app form errors
const (
	ValidateFormFailed         RetCode = iota + 41100
	MissRequiredFormField              // 缺少必填的表单项
	InvalidFormContent                 // 非法表单内容
	UnAuthenticatedFormAccount         // 用户名密码验证不通过
	UnAuthenticatedFormToken           // Token验证不通过
)
