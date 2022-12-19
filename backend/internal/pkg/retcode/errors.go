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
	ParseCertificate                                  // 证书解析失败
	ParsePrivateKey                                   // 密钥解析失败
)
