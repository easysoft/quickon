<?php
/**
 * The router file of QuCheng.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     QuCheng
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
/* Set the error reporting. */
error_reporting(E_ALL);
define('IN_USE', true);

/* Start output buffer. */
ob_start();

/* Set cookie_httponly. */
ini_set("session.cookie_httponly", 1);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');

/* installed or not. */
//if(!isset($config->installed) or !$config->installed) die(header('location: install.php'));

/* Check for need upgrade. */
$config->installedVersion = $app->getInstalledVersion();
if($config->version != $config->installedVersion)
{
    $upgradeHtml = <<<UPGRADE
<html><head><meta charset='utf-8'></head>
  <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div>
      <div style='margin: 20px auto;'>
        <p>系统已更新，请登录后台运行升级指令:<code style='padding: 5px; border-radius: 4px; background-color: #ddd;'>q manage upgrade</code></p>
        <p>The system has been updated, Please run command in console:<code style='padding: 5px; border-radius: 4px; background-color: #ddd;'>q manage upgrade</code></p>
      <div>
    </div>
  </body>
</html>
UPGRADE;
    die($upgradeHtml);
}

/* Run the app. */
$common = $app->loadCommon();

/* Check the request is getconfig or not. */
if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die(helper::removeUTF8Bom($app->exportConfig()));

/* Remove install.php and upgrade.php. */
if(file_exists('install.php') or file_exists('upgrade.php'))
{
    $undeletedFiles = array();
    if(file_exists('install.php')) $undeletedFiles[] = '<strong style="color:#ed980f">install.php</strong>';
    if(file_exists('upgrade.php')) $undeletedFiles[] = '<strong style="color:#ed980f">upgrade.php</strong>';
    $wwwDir = dirname(__FILE__);
    if($undeletedFiles)
    {
        echo "<html><head><meta charset='utf-8'></head>
            <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>";
        echo "<div style='margin-bottom:8px;'>安全起见，请删除 <strong style='color:#ed980f'>{$wwwDir}</strong> 目录下的 " . join(' 和 ', $undeletedFiles) . " 文件。</div>";
        echo "<div>Please remove " . join(' and ', $undeletedFiles) . " under <strong style='color:#ed980f'>$wwwDir</strong> dir for security reason.</div>";
        die("</td></tr></table></body></html>");
    }
}

/* If client device is mobile and version is pro, set the default view as mthml. */
if($app->clientDevice == 'mobile' and (strpos($config->version, 'pro') === 0 or strpos($config->version, 'biz') === 0 or strpos($config->version, 'max') === 0) and $config->default->view == 'html') $config->default->view = 'mhtml';
if(!empty($_GET['display']) && $_GET['display'] == 'card') $config->default->view = 'xhtml';

$app->parseRequest();
$common->checkPriv();
$common->checkIframe();
$app->loadModule();

/* Flush the buffer. */
echo helper::removeUTF8Bom(ob_get_clean());
