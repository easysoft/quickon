package tls

import (
	"crypto/rand"
	"crypto/rsa"
	"crypto/sha512"
	"crypto/x509"
	"encoding/pem"
	"github.com/pkg/errors"
	"time"
)

type tlsKeyPair struct {
	certificate []byte
	privateKey  []byte

	Certificates []*x509.Certificate
	PrivateKey   *rsa.PrivateKey
}

func Parse(cert, key []byte) (*tlsKeyPair, error) {
	t := &tlsKeyPair{
		certificate: cert,
		privateKey:  key,
	}

	var err error

	t.Certificates, err = parseCertificate(cert)
	if err != nil {
		return nil, err
	}

	t.PrivateKey, err = parseKey(key)
	if err != nil {
		return nil, err
	}

	return t, nil
}

func parseCertificate(cert []byte) ([]*x509.Certificate, error) {
	block, rest := pem.Decode(cert)
	if block == nil {
		return nil, errors.Errorf("decode certificate failed, rest: %s", rest)
	}

	certificates, err := x509.ParseCertificates(block.Bytes)
	if err != nil {
		return nil, err
	}

	return certificates, err
}

func parseKey(key []byte) (*rsa.PrivateKey, error) {
	block, rest := pem.Decode(key)
	if block == nil {
		return nil, errors.Errorf("decode private key failed, rest: %s", rest)
	}

	return x509.ParsePKCS1PrivateKey(block.Bytes)
}

func validCertificates(certs []*x509.Certificate) error {
	domainCert := certs[0]
	if time.Now().After(domainCert.NotAfter) {
		return ErrExpiredCertificate
	}

	lastCert := *domainCert

	for _, cert := range certs[1:] {
		if string(lastCert.AuthorityKeyId) != string(cert.SubjectKeyId) {
			return ErrUnmatchedCertificate
		}

		lastCert = *cert
	}

	//if !lastCert.IsCA {
	//	return ErrIncompleteCertificateChain
	//}
	return nil
}

func (t *tlsKeyPair) Valid() error {
	return validCertificates(t.Certificates)
}

func (t *tlsKeyPair) Encrypt(content []byte) ([]byte, error) {
	hash := sha512.New()
	pub, _ := t.Certificates[0].PublicKey.(*rsa.PublicKey)

	ciphertext, err := rsa.EncryptOAEP(hash, rand.Reader, pub, content, nil)
	if err != nil {
		return nil, err
	}
	return ciphertext, nil
}

func (t *tlsKeyPair) Decrypt(content []byte) ([]byte, error) {
	hash := sha512.New()
	plaintext, err := rsa.DecryptOAEP(hash, rand.Reader, t.PrivateKey, content, nil)
	if err != nil {
		return nil, err
	}
	return plaintext, nil
}

func (t *tlsKeyPair) GetCertInfo() CertInfo {
	cert := t.Certificates[0]
	return CertInfo{
		Sans:      cert.DNSNames,
		NotBefore: cert.NotBefore.Unix(),
		NotAfter:  cert.NotAfter.Unix(),
		Subject: SubjectInfo{
			CN: cert.Subject.CommonName,
			O:  cert.Subject.Organization,
			OU: cert.Subject.OrganizationalUnit,
		},
	}
}

type CertInfo struct {
	Sans      []string    `json:"sans,omitempty"`
	NotBefore int64       `json:"not_before,omitempty"`
	NotAfter  int64       `json:"not_after,omitempty"`
	Subject   SubjectInfo `json:"subject"`
}

type SubjectInfo struct {
	CN string   `json:"cn,omitempty"`
	O  []string `json:"o,omitempty"`
	OU []string `json:"ou,omitempty"`
}
