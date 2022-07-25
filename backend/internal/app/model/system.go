package model

type ReqSystemUpdate struct {
	Channel string `json:"channel"`
	Version string `json:"version"`
}
