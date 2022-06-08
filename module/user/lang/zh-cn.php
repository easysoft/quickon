<?php
/**
 * The user module zh-cn file of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     user
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
$lang->user->common           = '用户';
$lang->user->id               = '用户编号';
$lang->user->inside           = '内部人员';
$lang->user->outside          = '外部人员';
$lang->user->company          = '所属公司';
$lang->user->dept             = '部门';
$lang->user->account          = '用户名';
$lang->user->password         = '密码';
$lang->user->password2        = '请重复密码';
$lang->user->role             = '职位';
$lang->user->group            = '权限分组';
$lang->user->realname         = '姓名';
$lang->user->nickname         = '昵称';
$lang->user->commiter         = '源代码帐号';
$lang->user->birthyear        = '出生年';
$lang->user->gender           = '性别';
$lang->user->email            = '邮箱';
$lang->user->basicInfo        = '基本信息';
$lang->user->accountInfo      = '帐号信息';
$lang->user->verify           = '安全验证';
$lang->user->contactInfo      = '联系方式';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = '手机';
$lang->user->phone            = '电话';
$lang->user->weixin           = '微信';
$lang->user->dingding         = '钉钉';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = '通讯地址';
$lang->user->zipcode          = '邮编';
$lang->user->join             = '入职日期';
$lang->user->visits           = '访问次数';
$lang->user->visions          = '界面类型';
$lang->user->ip               = '最后IP';
$lang->user->last             = '最后登录';
$lang->user->ditto            = '同上';
$lang->user->originalPassword = '原密码';
$lang->user->newPassword      = '新密码';
$lang->user->verifyPassword   = '您的密码';
$lang->user->resetPassword    = '忘记密码';
$lang->user->score            = '积分';
$lang->user->name             = '名称';
$lang->user->type             = '用户类型';
$lang->user->cropAvatar       = '剪切头像';
$lang->user->cropAvatarTip    = '拖拽选框来选择头像剪切范围';
$lang->user->cropImageTip     = '所使用的头像图片过小，建议图片大小至少为 48x48，当前图片大小为 %s';
$lang->user->captcha          = '验证码';
$lang->user->avatar           = '用户头像';
$lang->user->birthday         = '生日';
$lang->user->nature           = '性格特征';
$lang->user->analysis         = '影响分析';
$lang->user->fails            = '失败次数';
$lang->user->locked           = '锁住日期';
$lang->user->clientStatus     = '登录状态';
$lang->user->clientLang       = '客户端语言';
$lang->user->identity         = '身份';

$lang->user->legendBasic        = '基本资料';

$lang->user->view          = "用户详情";
$lang->user->create        = "添加用户";
$lang->user->batchCreate   = "批量添加用户";
$lang->user->edit          = "编辑用户";
$lang->user->batchEdit     = "批量编辑";
$lang->user->unlock        = "解锁用户";
$lang->user->delete        = "删除用户";
$lang->user->login         = "用户登录";
$lang->user->mobileLogin   = "手机访问";
$lang->user->editProfile   = "编辑档案";
$lang->user->deny          = "访问受限";
$lang->user->confirmDelete = "您确定删除该用户吗？";
$lang->user->confirmUnlock = "您确定解除该用户的锁定状态吗？";
$lang->user->relogin       = "重新登录";
$lang->user->asGuest       = "游客访问";
$lang->user->goback        = "返回前一页";

$lang->user->saveTemplate          = '保存模板';
$lang->user->setPublic             = '设为公共模板';
$lang->user->setTemplateTitle      = '请输入模板标题';
$lang->user->applyTemplate         = '应用模板';
$lang->user->confirmDeleteTemplate = '您确认要删除该模板吗？';
$lang->user->setPublicTemplate     = '设为公共模板';
$lang->user->tplContentNotEmpty    = '模板内容不能为空!';

$lang->user->profile   = '档案';
$lang->user->issue     = '问题';
$lang->user->risk      = '风险';
$lang->user->schedule  = '日程';
$lang->user->dynamic   = '动态';

$lang->user->openedBy    = '由%s创建';
$lang->user->assignedTo  = '指派给%s';

$lang->user->errorDeny    = "抱歉，您无权访问『<b>%s</b>』模块的『<b>%s</b>』功能。请联系管理员获取权限。请回到地盘或重新登录。";
$lang->user->errorView    = "抱歉，您无权访问『<b>%s</b>』视图。请联系管理员获取权限。请回到地盘或重新登录。";
$lang->user->loginFailed  = "登录失败，请检查您的用户名或密码是否填写正确。";
$lang->user->lockWarning  = "您还有%s次尝试机会。";
$lang->user->loginLocked  = "密码尝试次数太多，请联系管理员解锁，或%s分钟后重试。";
$lang->user->weakPassword = "您的密码强度小于系统设定。";
$lang->user->errorWeak    = "密码不能使用【%s】这些常用弱口令。";
$lang->user->errorCaptcha = "验证码不正确！";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = '研发';
$lang->user->roleList['qa']     = '测试';
$lang->user->roleList['pm']     = '项目经理';
$lang->user->roleList['po']     = '产品经理';
$lang->user->roleList['td']     = '研发主管';
$lang->user->roleList['pd']     = '产品主管';
$lang->user->roleList['qd']     = '测试主管';
$lang->user->roleList['top']    = '高层管理';
$lang->user->roleList['others'] = '其他';

$lang->user->genderList['m'] = '男';
$lang->user->genderList['f'] = '女';

$lang->user->thirdPerson['m'] = '他';
$lang->user->thirdPerson['f'] = '她';

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>弱</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>中</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>强</span>";

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '删除';

$lang->user->keepLogin['on']   = '保持登录';
$lang->user->loginWithDemoUser = '使用demo帐号登录：';
$lang->user->scanToLogin       = '扫一扫登录';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = '类型';
$lang->user->tpl->title   = '模板名';
$lang->user->tpl->content = '内容';
$lang->user->tpl->public  = '是否公开';

$lang->usertpl = new stdclass();
$lang->usertpl->title = '模板名称';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、数字和下划线的组合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->role      = '职位影响内容和用户列表的顺序。';
$lang->user->placeholder->group     = '分组决定用户的权限列表。';
$lang->user->placeholder->commiter  = '版本控制系统(subversion)中的帐号';
$lang->user->placeholder->verify    = '请输入您的系统登录密码';

$lang->user->placeholder->loginPassword = '请输入密码';
$lang->user->placeholder->loginAccount  = '请输入用户名';
$lang->user->placeholder->loginUrl      = '请输入系统网址';

$lang->user->placeholder->passwordStrength[1] = '6位以上，包含大小写字母，数字。';
$lang->user->placeholder->passwordStrength[2] = '10位以上，包含大小写字母，数字，特殊字符。';

$lang->user->error = new stdclass();
$lang->user->error->account        = "【ID %s】的用户名应该为：三位以上的英文、数字或下划线的组合";
$lang->user->error->accountDupl    = "【ID %s】的用户名已经存在";
$lang->user->error->realname       = "【ID %s】的真实姓名必须填写";
$lang->user->error->visions        = "【ID %s】的界面类型必须填写";
$lang->user->error->password       = "【ID %s】的密码必须为六位及以上";
$lang->user->error->mail           = "【ID %s】的邮箱地址不正确";
$lang->user->error->reserved       = "【ID %s】的用户名已被系统预留";
$lang->user->error->weakPassword   = "【ID %s】的密码强度小于系统设定。";
$lang->user->error->dangerPassword = "【ID %s】的密码不能使用【%s】这些常用若口令。";

$lang->user->error->url              = "网址不正确，请联系管理员";
$lang->user->error->verify           = "用户名或密码错误";
$lang->user->error->verifyPassword   = "验证失败，请检查您的系统登录密码是否正确";
$lang->user->error->originalPassword = "原密码不正确";
$lang->user->error->companyEmpty     = "公司名称不能为空！";

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->resetFail        = "重置密码失败，检查用户名是否存在！";
$lang->user->resetSuccess     = "重置密码成功，请用新密码登录。";
$lang->user->noticeDelete     = "你确认要把“%s”从系统中删除吗？";
$lang->user->noticeResetFile  = "<h5>普通用户请联系管理员重置密码</h5>
    <h5>管理员请登录服务器，创建<span> '%s' </span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>";
$lang->user->mkdirWin = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    </td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div style='margin-bottom:8px;'>命令为：<strong style='color:#ed980f'>chmod o=rwx -R %s</strong>。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    <div style='margin-bottom:8px;'>Commond: <strong style='color:#ed980f'>chmod o=rwx -R %s</strong>.</div>
    </td></tr></table></body></html>
EOT;
