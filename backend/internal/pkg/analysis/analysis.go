package analysis

import (
	"context"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/analysis/client"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
	"time"
)

type Analysis struct {
	queue          []string
	maxQueueLength int
	bufferChan     chan string
	flushInterval  int64
	client         *client.QuickonMarketClient

	disabled bool
}

var _analysis *Analysis

func Init() *Analysis {
	_analysis = &Analysis{
		queue:          make([]string, 0),
		maxQueueLength: 1024,
		bufferChan:     make(chan string),
		flushInterval:  5,
		client:         client.NewClient(),
		disabled:       false,
	}
	return _analysis
}

func (s *Analysis) write(data string) {
	if !s.disabled {
		logging.DefaultLogger().Infof("content %s", data)
		s.bufferChan <- data
	}
}

func (s *Analysis) Run(ctx context.Context) {
	s.process(ctx)
}

func (s *Analysis) process(ctx context.Context) {
	ticker := time.NewTicker(time.Duration(s.flushInterval) * time.Millisecond)
loop:
	for {
		select {
		case l := <-s.bufferChan:
			s.queue = append(s.queue, l)
			if len(s.queue) >= s.maxQueueLength {
				s.flush()
			}
		case <-ticker.C:
			s.flush()
		case <-ctx.Done():
			ticker.Stop()
			s.flush()
			break loop
		}
	}
}

func (s *Analysis) flush() {
	for _, item := range s.queue {
		body := item
		s.client.Send(body)
	}
	s.queue = s.queue[:0]
}
