<?php
$config->installed    = true;
$config->debug        = getenv('PHP_DEBUG');
$config->requestType  = 'PATH_INFO';
$config->timezone     = 'Asia/Shanghai';
$config->db->host     = getenv('MYSQL_HOST');
$config->db->port     = getenv('MYSQL_PORT');
$config->db->name     = getenv('MYSQL_DB');
$config->db->user     = getenv('MYSQL_USER');
$config->db->encoding = 'UTF8';
$config->db->password = getenv('MYSQL_PASSWORD');
$config->db->prefix   = 'q_';
$config->webRoot      = getWebRoot();
$config->edition      = getenv('QUICKON_EDITION');

$config->CNE->api->host   = getenv('CNE_API_HOST');
if(getenv('CNE_API_TOKEN'))         $config->CNE->api->token   = getenv('CNE_API_TOKEN');
if(getenv('CLOUD_DEFAULT_CHANNEL')) $config->CNE->api->channel = getenv('CLOUD_DEFAULT_CHANNEL');

$config->cloud->api->host = getenv('CLOUD_API_HOST');
if(getenv('CLOUD_API_TOKEN'))       $config->cloud->api->token   = getenv('CLOUD_API_TOKEN');
if(getenv('CLOUD_DEFAULT_CHANNEL')) $config->cloud->api->channel = getenv('CLOUD_DEFAULT_CHANNEL');

$config->cloud->api->switchChannel = getenv('CLOUD_SWITCH_CHANNEL') == 'true' || getenv('CLOUD_SWITCH_CHANNEL') === true || getenv('CLOUD_SWITCH_CHANNEL') == 'on';

$config->default->lang = 'zh-cn';

$config->demoAccounts= getenv('DEMO_ACCOUNTS') ?  getenv('DEMO_ACCOUNTS') : '';

getenv('CONTENT_SECURITY_SCRIPT') && $config->CSPs[] = getenv('CONTENT_SECURITY_SCRIPT');
getenv('CONTENT_SECURITY_STYLE')  && $config->CSPs[] = getenv('CONTENT_SECURITY_STYLE');
getenv('CONTENT_SECURITY_IMG')    && $config->CSPs[] = getenv('CONTENT_SECURITY_IMG');
