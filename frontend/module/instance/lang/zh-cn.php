<?php
$lang->instance = new stdclass;
$lang->instance->name       = '名称';
$lang->instance->appName    = '应用模板';
$lang->instance->version    = '版本';
$lang->instance->status     = '状态';
$lang->instance->cpu        = 'CPU';
$lang->instance->mem        = '内存';
$lang->instance->space      = '空间';
$lang->instance->domain     = '域名';

$lang->instance->serviceInfo      = '服务信息';
$lang->instance->appTemplate      = '应用模板';
$lang->instance->source           = '来源';
$lang->instance->installAt        = '部署时间';
$lang->instance->runDuration      = '已运行';
$lang->instance->defaultAccount   = '默认用户';
$lang->instance->defaultPassword  = '默认密码';
$lang->instance->operationLog     = '操作记录';
$lang->instance->installedService = '已安装服务';
$lang->instance->installApp       = '安装应用';

$lang->instance->log = new stdclass;
$lang->instance->log->date    = '日期';
$lang->instance->log->message = '内容';

$lang->instance->actionList = array();
$lang->instance->actionList['install']   = '安装了%s';
$lang->instance->actionList['uninstall'] = '卸载了%s';
$lang->instance->actionList['start']     = '启动了%s';
$lang->instance->actionList['stop']      = '关闭了%s';
$lang->instance->actionList['editname']  = '修改了名称';
$lang->instance->actionList['upgrade']   = '升级了%s';

$lang->instance->sourceList = array();
$lang->instance->sourceList['cloud'] = '渠成公共市场';
$lang->instance->sourceList['local'] = '本地市场';

$lang->instance->statusList = array();
$lang->instance->statusList['installationFail'] = '安装失败';
$lang->instance->statusList['creating']         = '创建中';
$lang->instance->statusList['initializing']     = '初始化';
$lang->instance->statusList['startup']          = '启动中';
$lang->instance->statusList['starting']         = '启动中';
$lang->instance->statusList['running']          = '运行中';
$lang->instance->statusList['suspending']       = '暂停中';
$lang->instance->statusList['suspended']        = '已暂停';
$lang->instance->statusList['installing']       = '安装中';
$lang->instance->statusList['uninstalling']     = '卸载中';
$lang->instance->statusList['stopping']         = '关闭中';
$lang->instance->statusList['stopped']          = '已关闭';
$lang->instance->statusList['destroying']       = '销毁中';
$lang->instance->statusList['destroyed']        = '已销毁';
$lang->instance->statusList['abnormal']         = '异常';
$lang->instance->statusList['unknown']          = '未知';

$lang->instance->htmlStatuses = array();
$lang->instance->htmlStatuses['running']          = "<span><i class='icon icon-check-circle status-green'></i></span>";
$lang->instance->htmlStatuses['stopped']          = "<span><i class='icon icon-off status-gray'></i></span>";
$lang->instance->htmlStatuses['abnormal']         = "<span><i class='icon icon-close-circle status-red'></i></span>";
$lang->instance->htmlStatuses['installationFail'] = $lang->instance->htmlStatuses['abnormal'];
$lang->instance->htmlStatuses['busy']             = "<span><i class='icon icon-spinner-indicator icon-spin'></i></span>";

$lang->instance->componentFields = array();
$lang->instance->componentFields['replicas']  = '副本数';
$lang->instance->componentFields['cpu_limit'] = 'CPU';
$lang->instance->componentFields['mem_limit'] = '内存';

$lang->instance->start         = '启动';
$lang->instance->restart       = '重启';
$lang->instance->stop          = '关闭';
$lang->instance->install       = '安装';
$lang->instance->update        = '更新';
$lang->instance->upgrade       = '升级';
$lang->instance->customInstall = '自定义安装';
$lang->instance->uninstall     = '卸载';
$lang->instance->visit         = '访问';
$lang->instance->editName      = '修改名称';
$lang->instance->cpuCore       = '核';

$lang->instance->notices = array();
$lang->instance->notices['success']          = '成功';
$lang->instance->notices['fail']             = '失败';
$lang->instance->notices['confirmStart']     = '确定启动该应用吗？';
$lang->instance->notices['confirmStop']      = '确定关闭该应用吗？';
$lang->instance->notices['confirmUninstall'] = '确定卸载该应用吗？';
$lang->instance->notices['startSuccess']     = '启动成功';
$lang->instance->notices['startFail']        = '启动失败';
$lang->instance->notices['stopSuccess']      = '关闭成功';
$lang->instance->notices['stopFail']         = '关闭失败';
$lang->instance->notices['uninstallSuccess'] = '卸载成功';
$lang->instance->notices['uninstallFail']    = '卸载失败';
$lang->instance->notices['installSuccess']   = '安装成功';
$lang->instance->notices['installFail']      = '安装失败';
$lang->instance->notices['confirmInstall']   = '确定要安装(%s)?';
$lang->instance->notices['submiting']        = '提交中，请稍候...';
$lang->instance->notices['confirmUpgrade']   = '确定要升级 %s 到 %s 吗?';
$lang->instance->notices['upgradeSuccess']   = '升级成功';
$lang->instance->notices['upgradeFail']      = '升级失败';

$lang->instance->nameChangeTo    = ' %s 修改为 %s  。';
$lang->instance->versionChangeTo = ' %s 升级为 %s  。';

$lang->instance->instanceNotExists = '服务不存在';
$lang->instance->domainExists      = '域名已被占用，请使用其它域名。';
$lang->instance->caplicasTooSmall  = '副本数不能小于1';
$lang->instance->empty             = '暂无服务';
$lang->instance->noComponent       = '无组件，点击';
$lang->instance->noHigherVersion   = '未找到更高版本！';

$lang->instance->errors = new stdclass;
$lang->instance->errors->domainLength         = '域名长度必须介于2-20字符之间';
$lang->instance->errors->wrongDomainCharacter = '域名只能是英文字母和数字';
$lang->instance->errors->noAppInfo            = '获取应用数据失败，请稍候重试。';
