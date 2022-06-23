#!/bin/bash

set -xe

addlicense -f licenses/z-public-1.2.tpl -ignore web/** -ignore "**/*.md" -ignore vendor/** -ignore "**/*.yml" -ignore "**/*.yaml" -ignore "**/*.sh" ./**
