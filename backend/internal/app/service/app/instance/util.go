package instance

import (
	"context"
	"encoding/json"
	"fmt"
	"github.com/sirupsen/logrus"
	"gitlab.zcorp.cc/pangu/cne-api/internal/pkg/kube/cluster"
	"gopkg.in/yaml.v3"
	"helm.sh/helm/v3/pkg/release"
	"io/ioutil"
	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/apimachinery/pkg/labels"
	"k8s.io/apimachinery/pkg/types"
	"strconv"
)

func loadAppSecret(ctx context.Context, name, namespace string, revision int, ks *cluster.Cluster) (*v1.Secret, error) {
	var targetSecret *v1.Secret

	selector := labels.Set{"name": name, "owner": "helm", "version": strconv.Itoa(revision)}.AsSelector()
	secrets, err := ks.Store.ListSecrets(namespace, selector)
	if err != nil {
		return nil, err
	}

	count := len(secrets)
	if count == 1 {
		targetSecret = secrets[0]
	}

	if targetSecret == nil {
		secretList, err := ks.Clients.Base.CoreV1().Secrets(namespace).List(ctx, metav1.ListOptions{LabelSelector: selector.String()})
		if err != nil {
			return nil, err
		}
		count = len(secretList.Items)
		if count != 1 {
			e := fmt.Errorf("get release secret failed, expect 1 got %d", count)
			return nil, e
		}
		targetSecret = &secretList.Items[0]
	}

	return targetSecret, nil
}

func writeValuesFile(data map[string]interface{}) (string, error) {
	f, err := ioutil.TempFile("/tmp", "values.******.yaml")
	if err != nil {
		return "", err
	}
	vars, err := yaml.Marshal(data)
	if err != nil {
		return "nil", err
	}
	_, err = f.Write(vars)
	if err != nil {
		return "nil", err
	}
	_ = f.Close()
	return f.Name(), nil
}

func completeAppLabels(ctx context.Context, rel *release.Release, ks *cluster.Cluster, logger logrus.FieldLogger, meta metav1.ObjectMeta) error {
	logger.Info("start complete app labels")
	latestSecret, err := loadAppSecret(ctx, rel.Name, rel.Namespace, rel.Version, ks)
	if err != nil {
		return err
	}

	t := struct {
		Metadata metav1.ObjectMeta `json:"metadata"`
	}{meta}
	patchContent, _ := json.Marshal(&t)
	_, err = ks.Clients.Base.CoreV1().Secrets(latestSecret.Namespace).Patch(ctx, latestSecret.Name, types.MergePatchType, patchContent, metav1.PatchOptions{})
	return err
}
