<?php
/**
 * The admin module zh-cn file of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
$lang->admin->index         = '后台管理首页';
$lang->admin->register      = '登记';

$lang->admin->api     = '接口';
$lang->admin->log     = '日志';
$lang->admin->setting = '设置';
$lang->admin->days    = '日志保存天数';

$lang->admin->initAdmin = '初始化管理员账号';
$lang->admin->password2 = '确认密码';
$lang->admin->submit    = '提交';
$lang->admin->account   = '管理员帐号';
$lang->admin->password  = '管理员密码';
$lang->admin->password2 = '确认密码';

$lang->admin->errorEmpty['account']   = "{$lang->admin->account}不能为空";
$lang->admin->errorEmpty['password']  = "{$lang->admin->password}不能为空";
$lang->admin->errorEmpty['password2'] = "{$lang->admin->password2}不能为空";

$lang->admin->errorDiffPasswords = "{$lang->admin->password}与{$lang->admin->password2}不一致";

$lang->admin->info = new stdclass();
$lang->admin->info->version = '当前系统的版本是%s';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->log     = '超出存天数的日志会被删除，需要开启计划任务。';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common       = '安全策略';
$lang->admin->safe->set          = '密码安全设置';
$lang->admin->safe->password     = '密码安全';
$lang->admin->safe->weak         = '常用弱口令';
$lang->admin->safe->reason       = '类型';
$lang->admin->safe->checkWeak    = '弱口令扫描';
$lang->admin->safe->changeWeak   = '修改弱口令密码';
$lang->admin->safe->loginCaptcha = '登录使用验证码';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登录修改密码';

$lang->admin->safe->modeList[0] = '不检查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '强';

$lang->admin->safe->modeRuleList[1] = '6位以上，包含大小写字母，数字。';
$lang->admin->safe->modeRuleList[2] = '10位以上，包含字母，数字，特殊字符。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '与帐号相同';
$lang->admin->safe->reasonList['mobile']   = '与手机相同';
$lang->admin->safe->reasonList['phone']    = '与电话相同';
$lang->admin->safe->reasonList['birthday'] = '与生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必须修改';
$lang->admin->safe->modifyPasswordList[0] = '不强制';

$lang->admin->safe->loginCaptchaList[1] = '是';
$lang->admin->safe->loginCaptchaList[0] = '否';

$lang->admin->safe->noticeMode   = '系统会在登录、创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeStrong = '密码长度越长，含有大写字母或数字或特殊符号越多，密码字母越不重复，安全度越强！';
