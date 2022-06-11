<?php
/**
 * The action module zh-cn file of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     action
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
$lang->action->common        = '系统日志';
$lang->action->id            = '编号';
$lang->action->objectType    = '对象类型';
$lang->action->objectID      = '对象ID';
$lang->action->objectName    = '对象名称';
$lang->action->actor         = '操作者';
$lang->action->action        = '动作';
$lang->action->actionID      = '记录ID';
$lang->action->date          = '日期';
$lang->action->extra         = '附加值';
$lang->action->system        = '系统';
$lang->action->url           = '网址';
$lang->action->contentType   = '内容类型';
$lang->action->data          = '数据';
$lang->action->result        = '结果';
$lang->action->modified      = '修改了';
$lang->action->old           = '旧值为';
$lang->action->new           = '新值为';
$lang->action->to            = '到';

$lang->action->history = new stdclass();
$lang->action->history->action = '关联日志';
$lang->action->history->field  = '字段';
$lang->action->history->old    = '旧值';
$lang->action->history->new    = '新值';
$lang->action->history->diff   = '不同';

$lang->action->dynamic = new stdclass();
$lang->action->dynamic->today      = '今天';
$lang->action->dynamic->yesterday  = '昨天';
$lang->action->dynamic->twoDaysAgo = '前天';
$lang->action->dynamic->thisWeek   = '本周';
$lang->action->dynamic->lastWeek   = '上周';
$lang->action->dynamic->thisMonth  = '本月';
$lang->action->dynamic->lastMonth  = '上月';
$lang->action->dynamic->all        = '所有';
$lang->action->dynamic->hidden     = '已隐藏';
$lang->action->dynamic->search     = '搜索';

$lang->action->periods['all']       = $lang->action->dynamic->all;
$lang->action->periods['today']     = $lang->action->dynamic->today;
$lang->action->periods['yesterday'] = $lang->action->dynamic->yesterday;
$lang->action->periods['thisweek']  = $lang->action->dynamic->thisWeek;
$lang->action->periods['lastweek']  = $lang->action->dynamic->lastWeek;
$lang->action->periods['thismonth'] = $lang->action->dynamic->thisMonth;
$lang->action->periods['lastmonth'] = $lang->action->dynamic->lastMonth;

$lang->action->objectTypes['user'] = '用户';

/* 用来描述操作历史记录。*/
$lang->action->desc = new stdclass();

/* 用来显示动态信息。*/
$lang->action->label                = new stdclass();
$lang->action->label->logout        = '退出系统';
$lang->action->label->instance      = '服务';
$lang->action->label->start         = '启动了';
$lang->action->label->stop          = '关闭了';
$lang->action->label->install       = '安装了';
$lang->action->label->uninstall     = '卸载了';
$lang->action->label->resetpassword = '重置了密码';
$lang->action->label->editname      = '修改了服务名称';

/* 动态信息按照对象分组 */
$lang->action->dynamicAction = new stdclass();
$lang->action->dynamicAction->user['created']   = '创建用户';
$lang->action->dynamicAction->user['edited']    = '编辑用户';
$lang->action->dynamicAction->user['deleted']   = '删除用户';
$lang->action->dynamicAction->user['login']     = '用户登录';
$lang->action->dynamicAction->user['logout']    = '用户退出';
$lang->action->dynamicAction->user['undeleted'] = '还原用户';
$lang->action->dynamicAction->user['hidden']    = '隐藏用户';

$lang->action->dynamicAction->entry['created'] = '添加应用';
$lang->action->dynamicAction->entry['edited']  = '编辑应用';

/* 用来生成相应对象的链接。*/
//$lang->action->label->user   = '用户|user|view|account=%s';
$lang->action->label->instance = '服务|instance|view|id=%s';

/* Object type. */
$lang->action->search = new stdclass();
$lang->action->search->objectTypeList['']     = '';
$lang->action->search->objectTypeList['user'] = '用户';

$lang->action->dynamicInfo   = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s <em>%s</em> %s <a href='%s' title='%s'>%s</a></span>";
$lang->action->noLinkDynamic = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s <em>%s</em> %s %s</span>";

$lang->action->skipFields = new stdclass;
$lang->action->skipFields->login         = array('objectLabel', 'objectName');
$lang->action->skipFields->logout        = array('objectLabel', 'objectName');
$lang->action->skipFields->editname      = array('objectLabel');
$lang->action->skipFields->resetpassword = array('objectLabel');
