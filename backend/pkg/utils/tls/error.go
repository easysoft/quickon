package tls

import "errors"

var (
	ErrUnmatchedCertificate       = errors.New("certificate chain not matched")
	ErrExpiredCertificate         = errors.New("certificate is expired")
	ErrIncompleteCertificateChain = errors.New("certificate chain is uncompleted")
)
