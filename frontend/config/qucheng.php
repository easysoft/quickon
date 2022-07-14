<?php
/**
* The config file. Don't modify this file directly, copy the item to my.php and change it.
*
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     config
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
$config->officalWebsite = 'https://www.qucheng.com';

$config->manualUrl['home']    = 'https://www.qucheng.com/book/quchenghelp.html?fullScreen=qucheng';
$config->manualUrl['install'] = 'https://www.qucheng.com/book/qucheng/qucheng-installation-11.html?fullScreen=qucheng';

/* Supported charsets. */
$config->charsets['zh-cn']['utf-8'] = 'UTF-8';
$config->charsets['zh-cn']['gbk']   = 'GBK';
$config->charsets['zh-tw']['utf-8'] = 'UTF-8';
$config->charsets['zh-tw']['big5']  = 'BIG5';
$config->charsets['en']['utf-8']    = 'UTF-8';
$config->charsets['en']['GBK']      = 'GBK';
$config->charsets['de']['utf-8']    = 'UTF-8';
$config->charsets['de']['GBK']      = 'GBK';
$config->charsets['fr']['utf-8']    = 'UTF-8';
$config->charsets['fr']['GBK']      = 'GBK';
$config->charsets['vi']['utf-8']    = 'UTF-8';
$config->charsets['vi']['GBK']      = 'GBK';
$config->charsets['ru']['utf-8']    = 'UTF-8';
$config->charsets['ru']['GBK']      = 'GBK';
$config->charsets['ja']['utf-8']    = 'UTF-8';
$config->charsets['ja']['GBK']      = 'GBK';
$config->charsets['es']['utf-8']    = 'UTF-8';
$config->charsets['es']['GBK']      = 'GBK';
$config->charsets['pt']['utf-8']    = 'UTF-8';
$config->charsets['pt']['GBK']      = 'GBK';

$config->openMethods = array();
$config->openMethods[] = 'gitlab.webhook';
$config->openMethods[] = 'upgrade.ajaxupdatefile';
$config->openMethods[] = 'user.login';
$config->openMethods[] = 'user.logout';
$config->openMethods[] = 'user.deny';
$config->openMethods[] = 'user.reset';
$config->openMethods[] = 'user.refreshrandom';
$config->openMethods[] = 'api.getsessionid';
$config->openMethods[] = 'misc.checktable';
$config->openMethods[] = 'misc.qrcode';
$config->openMethods[] = 'misc.about';
$config->openMethods[] = 'misc.checkupdate';
$config->openMethods[] = 'misc.ping';
$config->openMethods[] = 'misc.captcha';
$config->openMethods[] = 'misc.features';
$config->openMethods[] = 'misc.status';
$config->openMethods[] = 'file.read';
$config->openMethods[] = 'index.changelog';
$config->openMethods[] = 'my.changepassword';
$config->openMethods[] = 'my.profile';
$config->openMethods[] = 'my.settutorialconfig';
$config->openMethods[] = 'admin.ignore';
$config->openMethods[] = 'admin.init';
$config->openMethods[] = 'admin.resetpassword';
$config->openMethods[] = 'instance.apidetail';
$config->openMethods[] = 'backup.ajaxupgradestatus';

$config->notIframeMethods = array();
$config->notIframeMethods[] = 'admin.resetpassword';
$config->notIframeMethods[] = 'admin.init';
$config->notIframeMethods[] = 'instance.apidetail';
$config->notIframeMethods[] = 'user.login';
$config->notIframeMethods[] = 'user.deny';
$config->notIframeMethods[] = 'user.logout';
$config->notIframeMethods[] = 'my.changepassword';
$config->notIframeMethods[] = 'file.read';
$config->notIframeMethods[] = 'file.uploadimages';
$config->notIframeMethods[] = 'file.download';
$config->notIframeMethods[] = 'misc.status';
$config->notIframeMethods[] = 'backup.ajaxupgradestatus';

/* Define the tables. */
define('TABLE_USER',          '`' . $config->db->prefix . 'user`');
define('TABLE_COMPANY',       '`' . $config->db->prefix . 'company`');
define('TABLE_CONFIG',        '`' . $config->db->prefix . 'config`');
define('TABLE_GROUP',         '`' . $config->db->prefix . 'group`');
define('TABLE_GROUPPRIV',     '`' . $config->db->prefix . 'grouppriv`');
define('TABLE_USERGROUP',     '`' . $config->db->prefix . 'usergroup`');

define('TABLE_SPACE',         '`' . $config->db->prefix . 'space`');
define('TABLE_INSTANCE',      '`' . $config->db->prefix . 'instance`');
define('TABLE_BACKUP',        '`' . $config->db->prefix . 'bakcup`');

define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');

define('TABLE_ENTRY',         '`' . $config->db->prefix . 'entry`');

if(!defined('TABLE_LANG'))        define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');

$config->objectTables['instance']     = TABLE_INSTANCE;
$config->objectTables['space']        = TABLE_SPACE;
$config->objectTables['user']         = TABLE_USER;
$config->objectTables['entry']        = TABLE_ENTRY;
