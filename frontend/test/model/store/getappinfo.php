#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/store.class.php';
su('admin');

/**

title=测试 storeModel->getAppInfo();
cid=1
pid=1

*/

$storeClass = new storeTest();

$appInfo = $storeClass->getAppInfoTest(1);

r($appInfo) && p('id') && e('1');        //获取存在的应用，返回正确的应用id
