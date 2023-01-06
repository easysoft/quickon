package tls

import (
	"crypto"
	"crypto/dsa"
	"crypto/rand"
	"crypto/rsa"
	"crypto/sha512"
	"crypto/x509"
	"encoding/asn1"
	"encoding/pem"
	"fmt"
	"math/big"
	"strings"
	"time"

	"github.com/pkg/errors"
	"github.com/sirupsen/logrus"
)

type tlsKeyPair struct {
	certificate []byte
	privateKey  []byte

	Certificates []*x509.Certificate
	PrivateKey   crypto.PrivateKey
}

func Parse(cert, key []byte, logger logrus.FieldLogger) (*tlsKeyPair, error) {
	t := &tlsKeyPair{
		certificate: cert,
		privateKey:  key,
	}

	var err error

	t.Certificates, err = parseCertificate(cert)
	if err != nil {
		logger.WithError(err).Error("parse certificate failed")
		return nil, ErrParseCertificate
	}

	t.PrivateKey, err = parseKey(key)
	if err != nil {
		logger.WithError(err).Error("parse private key failed")
		return nil, ErrParsePrivateKey
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

type DSAKeyFormat struct {
	Version       int
	P, Q, G, Y, X *big.Int
}

func parseKey(key []byte) (crypto.PrivateKey, error) {
	block, _ := pem.Decode(key)
	if block == nil {
		return nil, errors.New("no PEM data in input")
	}

	if block.Type == "PRIVATE KEY" {
		priv, err := x509.ParsePKCS8PrivateKey(block.Bytes)
		if err != nil {
			return nil, fmt.Errorf("decoding PEM as PKCS#8: %s", err)
		}
		return priv, nil
	} else if !strings.HasSuffix(block.Type, " PRIVATE KEY") {
		return nil, fmt.Errorf("no private key data in PEM block of type %s", block.Type)
	}

	switch block.Type[:len(block.Type)-12] { // strip " PRIVATE KEY"
	case "RSA":
		priv, err := x509.ParsePKCS1PrivateKey(block.Bytes)
		if err != nil {
			return nil, fmt.Errorf("parsing RSA private key from PEM: %s", err)
		}
		return priv, nil
	case "EC":
		priv, err := x509.ParseECPrivateKey(block.Bytes)
		if err != nil {
			return nil, fmt.Errorf("parsing EC private key from PEM: %s", err)
		}
		return priv, nil
	case "DSA":
		var k DSAKeyFormat
		_, err := asn1.Unmarshal(block.Bytes, &k)
		if err != nil {
			return nil, fmt.Errorf("parsing DSA private key from PEM: %s", err)
		}
		priv := &dsa.PrivateKey{
			PublicKey: dsa.PublicKey{
				Parameters: dsa.Parameters{
					P: k.P, Q: k.Q, G: k.G,
				},
				Y: k.Y,
			},
			X: k.X,
		}
		return priv, nil
	default:
		return nil, fmt.Errorf("invalid private key type %s", block.Type)
	}
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
	plaintext, err := rsa.DecryptOAEP(hash, rand.Reader, t.PrivateKey.(*rsa.PrivateKey), content, nil)
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
