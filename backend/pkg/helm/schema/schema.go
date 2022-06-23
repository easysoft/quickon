package schema

import (
	"helm.sh/helm/v3/pkg/chart"
	"sort"
	"strconv"
	"strings"
)

type Schema struct {
	Id       int
	Chart    string
	Category string
	Data     []byte
}

type Schemas map[string]Schema

func (ss Schemas) Categories() []string {
	var result = make([]string, 0)
	var tmpMap = make(map[int]string, 0)
	var tmpSlice = make([]int, 0)

	for category, schema := range ss {
		tmpMap[schema.Id] = category
		tmpSlice = append(tmpSlice, schema.Id)
	}

	sort.IntsAreSorted(tmpSlice)
	for _, index := range tmpSlice {
		result = append(result, tmpMap[index])
	}
	return result
}

func (ss Schemas) Has(category string) bool {
	_, ok := ss[category]
	return ok
}

func (ss Schemas) Read(category string) []byte {
	return ss[category].Data
}

func LoadSchema(ch *chart.Chart, category string) ([]byte, bool) {
	schemas := loadSchemas(ch)
	if schemas.Has(category) {
		return schemas.Read(category), true
	}
	return nil, false
}

func loadSchemas(ch *chart.Chart) Schemas {
	var schemas Schemas = make(map[string]Schema, 0)
	for _, file := range ch.Files {

		if strings.HasPrefix(file.Name, schemaDirName) {
			if strings.HasSuffix(file.Name, jsonSuffix) {
				l := len(file.Name)
				n := file.Name[len(schemaDirName)+1 : l-len(jsonSuffix)]
				frames := strings.Split(n, "-")
				if len(frames) == 2 {
					id, err := strconv.Atoi(frames[0])
					if err != nil {
						continue
					}
					category := frames[1]
					schema := Schema{Id: id, Category: category, Chart: ch.Name(), Data: file.Data}
					schemas[category] = schema
				}
			}
		}
	}

	return schemas
}
