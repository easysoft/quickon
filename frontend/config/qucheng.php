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
$config->openMethods[] = 'doc.selectlibtype';
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
define('TABLE_DEPT',          '`' . $config->db->prefix . 'dept`');
define('TABLE_CONFIG',        '`' . $config->db->prefix . 'config`');
define('TABLE_TODO',          '`' . $config->db->prefix . 'todo`');
define('TABLE_GROUP',         '`' . $config->db->prefix . 'group`');
define('TABLE_GROUPPRIV',     '`' . $config->db->prefix . 'grouppriv`');
define('TABLE_USERGROUP',     '`' . $config->db->prefix . 'usergroup`');
define('TABLE_USERQUERY',     '`' . $config->db->prefix . 'userquery`');
define('TABLE_USERCONTACT',   '`' . $config->db->prefix . 'usercontact`');
define('TABLE_USERVIEW',      '`' . $config->db->prefix . 'userview`');

define('TABLE_SPACE',         '`' . $config->db->prefix . 'space`');
define('TABLE_APP',           '`' . $config->db->prefix . 'app`');
define('TABLE_RELEASE',       '`' . $config->db->prefix . 'release`');
define('TABLE_INSTANCE',      '`' . $config->db->prefix . 'instance`');
define('TABLE_RELATION',      '`' . $config->db->prefix . 'relation`');
define('TABLE_CATEGORY',      '`' . $config->db->prefix . 'category`');

define('TABLE_BUG',           '`' . $config->db->prefix . 'bug`');
define('TABLE_CASE',          '`' . $config->db->prefix . 'case`');
define('TABLE_CASESTEP',      '`' . $config->db->prefix . 'casestep`');
define('TABLE_TESTTASK',      '`' . $config->db->prefix . 'testtask`');
define('TABLE_TESTRUN',       '`' . $config->db->prefix . 'testrun`');
define('TABLE_TESTRESULT',    '`' . $config->db->prefix . 'testresult`');
define('TABLE_USERTPL',       '`' . $config->db->prefix . 'usertpl`');

define('TABLE_PRODUCT',       '`' . $config->db->prefix . 'product`');
define('TABLE_BRANCH',        '`' . $config->db->prefix . 'branch`');
define('TABLE_EXPECT',        '`' . $config->db->prefix . 'expect`');
define('TABLE_STAGE',         '`' . $config->db->prefix . 'stage`');
define('TABLE_STAKEHOLDER',   '`' . $config->db->prefix . 'stakeholder`');
define('TABLE_STORY',         '`' . $config->db->prefix . 'story`');
define('TABLE_STORYSPEC',     '`' . $config->db->prefix . 'storyspec`');
define('TABLE_STORYREVIEW',   '`' . $config->db->prefix . 'storyreview`');
define('TABLE_STORYSTAGE',    '`' . $config->db->prefix . 'storystage`');
define('TABLE_STORYESTIMATE', '`' . $config->db->prefix . 'storyestimate`');
define('TABLE_PRODUCTPLAN',   '`' . $config->db->prefix . 'productplan`');
define('TABLE_PLANSTORY',     '`' . $config->db->prefix . 'planstory`');

define('TABLE_PROGRAM',       '`' . $config->db->prefix . 'project`');
define('TABLE_PROJECT',       '`' . $config->db->prefix . 'project`');
define('TABLE_EXECUTION',     '`' . $config->db->prefix . 'project`');
define('TABLE_TASK',          '`' . $config->db->prefix . 'task`');
define('TABLE_TASKSPEC',      '`' . $config->db->prefix . 'taskspec`');
define('TABLE_TEAM',          '`' . $config->db->prefix . 'team`');
define('TABLE_PROJECTPRODUCT','`' . $config->db->prefix . 'projectproduct`');
define('TABLE_PROJECTSTORY',  '`' . $config->db->prefix . 'projectstory`');
define('TABLE_PROJECTCASE',   '`' . $config->db->prefix . 'projectcase`');
define('TABLE_TASKESTIMATE',  '`' . $config->db->prefix . 'taskestimate`');
define('TABLE_EFFORT',        '`' . $config->db->prefix . 'effort`');
define('TABLE_BURN',          '`' . $config->db->prefix . 'burn`');
define('TABLE_BUILD',         '`' . $config->db->prefix . 'build`');
define('TABLE_ACL',           '`' . $config->db->prefix . 'acl`');

define('TABLE_DESIGN',          '`' . $config->db->prefix . 'design`');
define('TABLE_DESIGNSPEC',      '`' . $config->db->prefix . 'designspec`');
define('TABLE_DOCLIB',          '`' . $config->db->prefix . 'doclib`');
define('TABLE_DOC',             '`' . $config->db->prefix . 'doc`');
define('TABLE_API',             '`' . $config->db->prefix . 'api`');
define('TABLE_API_SPEC',        '`' . $config->db->prefix . 'apispec`');
define('TABLE_APISTRUCT',       '`' . $config->db->prefix . 'apistruct`');
define('TABLE_APISTRUCT_SPEC',  '`' . $config->db->prefix . 'apistruct_spec`');
define('TABLE_API_LIB_RELEASE', '`' . $config->db->prefix . 'api_lib_release`');

define('TABLE_MODULE',        '`' . $config->db->prefix . 'module`');
define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');
define('TABLE_HOLIDAY',       '`' . $config->db->prefix . 'holiday`');
define('TABLE_HISTORY',       '`' . $config->db->prefix . 'history`');
define('TABLE_EXTENSION',     '`' . $config->db->prefix . 'extension`');
define('TABLE_CRON',          '`' . $config->db->prefix . 'cron`');
define('TABLE_BLOCK',         '`' . $config->db->prefix . 'block`');
define('TABLE_DOCCONTENT',    '`' . $config->db->prefix . 'doccontent`');
define('TABLE_TESTSUITE',     '`' . $config->db->prefix . 'testsuite`');
define('TABLE_SUITECASE',     '`' . $config->db->prefix . 'suitecase`');
define('TABLE_TESTREPORT',    '`' . $config->db->prefix . 'testreport`');

define('TABLE_ENTRY',         '`' . $config->db->prefix . 'entry`');
define('TABLE_WEEKLYREPORT',  '`' . $config->db->prefix . 'weeklyreport`');
define('TABLE_LOG',           '`' . $config->db->prefix . 'log`');
define('TABLE_SCORE',         '`' . $config->db->prefix . 'score`');
define('TABLE_NOTIFY',        '`' . $config->db->prefix . 'notify`');
define('TABLE_OAUTH',         '`' . $config->db->prefix . 'oauth`');
define('TABLE_PIPELINE',      '`' . $config->db->prefix . 'pipeline`');
define('TABLE_JOB',           '`' . $config->db->prefix . 'job`');
define('TABLE_COMPILE',       '`' . $config->db->prefix . 'compile`');
define('TABLE_MR',            '`' . $config->db->prefix . 'mr`');
define('TABLE_MRAPPROVAL',    '`' . $config->db->prefix . 'mrapproval`');

if(!defined('TABLE_LANG'))        define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');

if(!defined('TABLE_SEARCHINDEX')) define('TABLE_SEARCHINDEX', $config->db->prefix . 'searchindex');
if(!defined('TABLE_SEARCHDICT'))  define('TABLE_SEARCHDICT',  $config->db->prefix . 'searchdict');

$config->objectTables['instance']     = TABLE_INSTANCE;
$config->objectTables['space']        = TABLE_SPACE;
$config->objectTables['user']         = TABLE_USER;
$config->objectTables['api']          = TABLE_API;
$config->objectTables['doc']          = TABLE_DOC;
$config->objectTables['doclib']       = TABLE_DOCLIB;
$config->objectTables['todo']         = TABLE_TODO;
$config->objectTables['custom']       = TABLE_LANG;
$config->objectTables['branch']       = TABLE_BRANCH;
$config->objectTables['module']       = TABLE_MODULE;
$config->objectTables['caselib']      = TABLE_TESTSUITE;
$config->objectTables['entry']        = TABLE_ENTRY;
$config->objectTables['stakeholder']  = TABLE_STAKEHOLDER;
$config->objectTables['job']          = TABLE_JOB;
$config->objectTables['team']         = TABLE_TEAM;
$config->objectTables['pipeline']     = TABLE_PIPELINE;
$config->objectTables['mr']           = TABLE_MR;
$config->objectTables['stage']        = TABLE_STAGE;
$config->objectTables['apistruct']    = TABLE_APISTRUCT;
