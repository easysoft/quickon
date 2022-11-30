#!/bin/bash

QuchengKey=$(cat /data/qucheng/backup/.key)

curl "http://localhost/instance-autoBackup-${QuchengKey}.html"
