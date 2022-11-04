<?php
$config->instance = new stdclass;
$config->instance->keepDomainList = array();
$config->instance->keepDomainList['console'] = 'console';
$config->instance->keepDomainList['demo']    = 'demo';
$config->instance->keepDomainList['s3']      = 's3';
$config->instance->keepDomainList['s3-api']  = 's3-api';

$config->instance->seniorChartList = array();
$config->instance->seniorChartList['zentao']     = ['zentao-biz', 'zentao-max'];
$config->instance->seniorChartList['zentao-biz'] = ['zentao-max'];
