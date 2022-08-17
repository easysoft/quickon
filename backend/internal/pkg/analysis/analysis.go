package analysis

import (
	"context"
	"time"

	"github.com/sirupsen/logrus"

	"gitlab.zcorp.cc/pangu/cne-api/pkg/httplib/market"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

type Analysis struct {
	queue          []string
	maxQueueLength int
	bufferChan     chan string
	flushInterval  int64
	client         *market.Client

	logger   *logrus.Logger
	disabled bool
}

var _analysis *Analysis

func Init() *Analysis {
	logger := logging.DefaultLogger()
	_analysis = &Analysis{
		queue:          make([]string, 0),
		maxQueueLength: 1024,
		bufferChan:     make(chan string),
		flushInterval:  1,
		client:         market.New(),
		logger:         logger,
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
	ticker := time.NewTicker(time.Duration(s.flushInterval) * time.Second)
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
	var err error
	length := len(s.queue)
	if length == 0 {
		return
	}

	s.logger.Infof("flush analysis data, %d records will be upload", length)
	for _, item := range s.queue {
		body := item
		err = s.client.SendAppAnalysis(body)
		if err != nil {
			s.logger.WithError(err).Error("request market api server failed")
		}
	}
	s.queue = s.queue[:0]
}
