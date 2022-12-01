#!/bin/bash

if [ -f "/data/qucheng/backup/.key" ]; then
  QuchengKey=$(cat /data/qucheng/backup/.key)
else
  QuchengKey=nokey
fi

curl "http://localhost/instance-autoBackup-${QuchengKey}.html"
