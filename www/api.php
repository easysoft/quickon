<?php
/**
 * The api router file of QuCheng.
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     QuCheng
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        https://www.qucheng.com
 */
/* Set the error reporting. */
error_reporting(E_ALL);
define('RUN_MODE', 'api');
/* Start output buffer. */
ob_start();

/* Load the framework. */
include '../framework/api/router.class.php';
include '../framework/api/entry.class.php';
include '../framework/api/helper.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'api');

/* Run the app. */
$common = $app->loadCommon();

/* Check entry. */
$common->checkEntry();
$common->loadConfigFromDB();

/* Set default params. */
if(!$app->version) $config->requestType = 'GET';
$config->default->view = 'json';

$app->parseRequest();

/* Old version need check priv here, new version check priv in entry. */
if(!$app->version) $common->checkPriv();

$app->loadModule();

$output = ob_get_clean();

/* Flush the buffer. */
echo $app->formatData(helper::removeUTF8Bom($output));
