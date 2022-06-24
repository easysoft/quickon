<?php
/**
 * The upgrade file of QuCheng on cli mode.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
if(PHP_SAPI != 'cli') die('Please run in CLI.');

define('IN_UPGRADE', true);
$rootPath = dirname(dirname(__FILE__));
$myConfig = "$rootPath/config/my.php";
if(!file_exists($myConfig)) die("[" . date('Y-m-d H:i:s') . "] ERROR: Not find {$myConfig}, Please create it firstly. \n");

error_reporting(E_ERROR);

/* Load the framework. */
include "$rootPath/framework/router.class.php";
include "$rootPath/framework/control.class.php";
include "$rootPath/framework/model.class.php";
include "$rootPath/framework/helper.class.php";

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');
$common = $app->loadCommon();

/* Reset the config params to make sure the install program will be lauched. */
$config->set('requestType', 'GET');
$config->set('default.module', 'upgrade');
$app->setDebug();

/* Check the installed version is the latest or not. */
$config->installedVersion = $common->loadModel('setting')->getVersion();

if(($config->version[0] == $config->installedVersion[0] or (is_numeric($config->version[0]) and is_numeric($config->installedVersion[0]))) and version_compare($config->version, $config->installedVersion) <= 0)
{
    die("[" . date('Y-m-d H:i:s') . "]INFO: The version is latest version($config->installedVersion) now.\n\n");
}

$common->loadModel('upgrade')->cliUpgrade();
