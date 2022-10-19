// Copyright (c) 2022 北京渠成软件有限公司 All rights reserved.
// Use of this source code is governed by Z PUBLIC LICENSE 1.2 (ZPL 1.2)
// license that can be found in the LICENSE file.

package instance

import (
	"fmt"

	v1 "k8s.io/api/core/v1"
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"

	"gitlab.zcorp.cc/pangu/cne-api/internal/app/model"
)

func (i *Instance) GetPvcList() []model.AppRespPvc {
	var result []model.AppRespPvc
	pvcList, err := i.Ks.Clients.Base.CoreV1().PersistentVolumeClaims(i.namespace).List(i.Ctx, metav1.ListOptions{LabelSelector: i.selector.String()})
	if err != nil {
		i.logger.WithError(err).Error("list pvc failed")
		return result
	}
	for _, pvc := range pvcList.Items {
		quantity := pvc.Spec.Resources.Requests[v1.ResourceStorage]
		p := model.AppRespPvc{
			Name: pvc.Name, VolumeName: pvc.Spec.VolumeName, AccessModes: pvc.Spec.AccessModes,
			Size: quantity.AsApproximateFloat64(),
			Path: fmt.Sprintf("%s-%s-%s", pvc.Namespace, pvc.Name, pvc.Spec.VolumeName),
		}
		if pvc.Spec.StorageClassName != nil {
			p.StorageClassName = *pvc.Spec.StorageClassName
		}
		result = append(result, p)
	}
	return result
}
