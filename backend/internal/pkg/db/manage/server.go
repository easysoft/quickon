package manage

import "fmt"

type serverInfo struct {
	host string
	port int32
}

func (s *serverInfo) Host() string {
	return s.host
}

func (s *serverInfo) Port() int32 {
	return s.port
}

func (s *serverInfo) Address() string {
	return fmt.Sprintf("%s:%d", s.host, s.port)
}
