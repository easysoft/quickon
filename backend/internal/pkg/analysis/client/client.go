package client

import "fmt"

type QuickonMarketClient struct {
}

func NewClient() *QuickonMarketClient {
	return &QuickonMarketClient{}
}

func (q *QuickonMarketClient) Send(body string) {
	fmt.Println(body)
}
