<?php
$config->action->objectNameFields['instance'] = 'name';
$config->action->objectNameFields['space']    = 'name';
$config->action->objectNameFields['user']     = 'account';

$config->action->commonImgSize = 870;

$config->action->majorList = array();
$config->action->majorList['instance'] = array('start', 'stop', 'install','uninstall',);

$config->action->needGetProjectType = '';
$config->action->needGetRelateField = '';
$config->action->noLinkModules      = '';
