package cron

var extraCronTasks = make([]task, 0)

type task struct {
	spec        string
	f           func()
	immediately bool
}

func Register(spec string, f func(), immediately bool) {
	t := task{
		spec:        spec,
		f:           f,
		immediately: immediately,
	}
	extraCronTasks = append(extraCronTasks, t)
}

func (c *Client) LoadExtra() {
	for _, t := range extraCronTasks {
		if t.immediately {
			c.AddWithRun(t.spec, t.f)
		} else {
			c.Add(t.spec, t.f)
		}
	}

	extraCronTasks = make([]task, 0)
}
