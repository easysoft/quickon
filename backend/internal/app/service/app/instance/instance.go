package instance

import (
	"context"

	"github.com/sirupsen/logrus"
	"helm.sh/helm/v3/pkg/release"
	v1 "k8s.io/api/core/v1"
	"k8s.io/apimachinery/pkg/labels"

	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/constant"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gitlab.zcorp.cc/pangu/cne-api/pkg/logging"
)

type Instance struct {
	Ctx context.Context

	clusterName string
	namespace   string
	name        string

	selector labels.Selector

	Ks *cluster.Cluster

	release *release.Release
	secret  *v1.Secret

	ChartName           string
	CurrentChartVersion string

	logger logrus.FieldLogger
}

func NewInstance(ctx context.Context, name string, clusterName, namespace string, ks *cluster.Cluster) (*Instance, error) {

	i := &Instance{
		Ctx:         ctx,
		clusterName: clusterName, namespace: namespace, name: name,
		Ks: ks,
		logger: logging.DefaultLogger().WithContext(ctx).WithFields(logrus.Fields{
			"name": name, "namespace": namespace,
		}),
	}

	i.logger.Debugf("start fetch release")
	i.release = i.fetchRelease()
	if i.release == nil {
		return nil, ErrAppNotFound
	}
	i.logger.Debugf("start prepare")
	err := i.prepare()
	return i, err
}

func (i *Instance) prepare() error {
	i.ChartName = i.release.Chart.Metadata.Name
	i.CurrentChartVersion = i.release.Chart.Metadata.Version

	i.selector = labels.Set{"release": i.name}.AsSelector()
	secret, err := loadAppSecret(i.Ctx, i.name, i.namespace, i.release.Version, i.Ks)
	if err != nil {
		i.logger.WithError(err).Errorf("got release secret failed with resivion %d", i.release.Version)
		return err
	}
	i.secret = secret
	return nil
}

func (i *Instance) fetchRelease() *release.Release {
	getter := &ReleaseGetter{
		namespace: i.namespace,
		store:     i.Ks.Store,
	}
	i.logger.Debug("start fetch release secrets")
	rel, err := getter.Last(i.name)
	if err != nil {
		i.logger.WithError(err).Error("parse release failed")
	}
	i.logger.Debug("end fetch release secrets")
	return rel
}

func (i *Instance) isApp() bool {
	v, ok := i.secret.Labels[constant.LabelApplication]
	if ok && v == "true" {
		return true
	}
	return false
}

func (i *Instance) GetLogger() logrus.FieldLogger {
	return i.logger
}
