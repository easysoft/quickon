<?php
$lang->instance = new stdclass;
$lang->instance->name             = '名称';
$lang->instance->appName          = '应用模板';
$lang->instance->version          = '版本';
$lang->instance->status           = '状态';
$lang->instance->cpu              = 'CPU';
$lang->instance->mem              = '内存';
$lang->instance->space            = '空间';
$lang->instance->domain           = '域名';
$lang->instance->dbType           = '数据库';
$lang->instance->advanceOption    = '高级选项';
$lang->instance->baseInfo         = '基本信息';
$lang->instance->backupAndRestore = '备份';
$lang->instance->advance          = '高级';
$lang->instance->enableLDAP       = '启用LDAP';
$lang->instance->linkLDAP         = '集成LDAP';
$lang->instance->enableSMTP       = '启用SMTP';
$lang->instance->customSetting    = '自定义配置';
$lang->instance->upgradeToSenior  = '升级到高级版';

$lang->instance->systemLDAPInactive = '未开启系统LDAP';
$lang->instance->toSystemLDAP       = '去启用';

$lang->instance->enableSMTP         = '启用SMTP';
$lang->instance->systemSMTPInactive = '未开启系统SMTP';
$lang->instance->toSystemSMTP       = '去启用';


$lang->instance->serviceInfo      = '服务信息';
$lang->instance->appTemplate      = '应用模板';
$lang->instance->source           = '来源';
$lang->instance->installAt        = '部署时间';
$lang->instance->runDuration      = '已运行';
$lang->instance->defaultAccount   = '默认用户';
$lang->instance->defaultPassword  = '默认密码';
$lang->instance->operationLog     = '操作记录';
$lang->instance->installedService = '已安装服务';
$lang->instance->runningService   = '运行中的服务';
$lang->instance->installApp       = '安装应用';
$lang->instance->cpuUsage         = 'CPU';
$lang->instance->memUsage         = '内存';
$lang->instance->currentMemory    = '当前内存';
$lang->instance->adjustMem        = '调整内存';
$lang->instance->saveSetting      = '保存设置';
$lang->instance->leftTime         = '剩余';
$lang->instance->switchTo         = '升级到';
$lang->instance->or               = '或';
$lang->instance->hasRead          = '已阅读';
$lang->instance->stopInstanceTips = '关闭服务后才能升级到高级版！';

$lang->instance->dbTypes = array();
$lang->instance->dbTypes['sharedDB']   = '共享数据库';
$lang->instance->dbTypes['unsharedDB'] = '独享数据库';

$lang->instance->backup = new stdclass;
$lang->instance->backup->common              = '备份';
$lang->instance->backup->date                = '备份时间';
$lang->instance->backup->operator            = '备份人';
$lang->instance->backup->type                = '备份类型';
$lang->instance->backup->backupStatus        = '备份状态';
$lang->instance->backup->restoreStatus       = '回滚状态';
$lang->instance->backup->restoreOperator     = '回滚人';
$lang->instance->backup->restoreTime         = '回滚时间';
$lang->instance->backup->action              = '操作';
$lang->instance->backup->restore             = '回滚';
$lang->instance->backup->restoreInfo         = '回滚信息';
$lang->instance->backup->delete              = '删除';
$lang->instance->backup->rebackup            = '重试备份';
$lang->instance->backup->create              = '创建备份';
$lang->instance->backup->database            = '数据库';
$lang->instance->backup->dbType              = '类型';
$lang->instance->backup->dbName              = '名称';
$lang->instance->backup->dbStatus            = '状态';
$lang->instance->backup->dbSpentSeconds      = '耗时(秒)';
$lang->instance->backup->dbSize              = '大小';
$lang->instance->backup->volumne             = '数据卷';
$lang->instance->backup->volName             = '名称';
$lang->instance->backup->volMountName        = '挂载目录';
$lang->instance->backup->volStatus           = '状态';
$lang->instance->backup->volSpentSeconds     = '耗时(秒)';
$lang->instance->backup->volSize             = '大小';
$lang->instance->backup->lastRestore         = '上次回滚';
$lang->instance->backup->restoreDate         = '回滚时间';
$lang->instance->backup->latestBackupAt      = '上次备份时间';
$lang->instance->backup->backupBeforeRestore = '回滚前建议您先备份!';
$lang->instance->backup->enableAutoBackup    = '开启自动备份';
$lang->instance->backup->autoBackup          = '自动备份';
$lang->instance->backup->cycleDays           = '备份周期';
$lang->instance->backup->backupTime          = '备份时间';
$lang->instance->backup->keepDays            = '保留天数';
$lang->instance->backup->keepDayRange        = '请输入1~30之间的整数';
$lang->instance->backup->firstStartTime      = '%s 首次备份将于%s 执行';
$lang->instance->backup->invalidTime         = '无效的时间';
$lang->instance->backup->disableAutoBackup   = '自动备份已关闭';

$lang->instance->backup->cycleList[1]  = '每日';

$lang->instance->backup->operators = array();
$lang->instance->backup->operators['auto'] = '自动备份';

$lang->instance->backup->statusList = array();
$lang->instance->backup->statusList['success']        = '成功';
$lang->instance->backup->statusList['failed']         = '失败';
$lang->instance->backup->statusList['pending']        = '等待中';
$lang->instance->backup->statusList['processing']     = '备份中';
$lang->instance->backup->statusList['inprogress']     = '备份中';
$lang->instance->backup->statusList['completed']      = '完成';
$lang->instance->backup->statusList['executedFailed'] = '失败';
$lang->instance->backup->statusList['uploading']      = '上传中';
$lang->instance->backup->statusList['uploadFailed']   = '上传失败';
$lang->instance->backup->statusList['downloading']    = '下载中';
$lang->instance->backup->statusList['downloadFailed'] = '下载失败';

$lang->instance->restore = new stdclass;
$lang->instance->restore->statusList = array();
$lang->instance->restore->statusList['pending']    = '等待中';
$lang->instance->restore->statusList['inprogress'] = '进行中';
$lang->instance->restore->statusList['completed']  = '完成';
$lang->instance->restore->statusList['failed']     = '失败';

$lang->instance->dbList     = '数据库';
$lang->instance->dbName     = '名称';
$lang->instance->dbStatus   = '状态';
$lang->instance->dbType     = '类型';
$lang->instance->action     = '操作';
$lang->instance->management = '管理';
$lang->instance->dbReady    = '正常';
$lang->instance->dbWaiting  = '等待就绪';

$lang->instance->log = new stdclass;
$lang->instance->log->date    = '日期';
$lang->instance->log->message = '内容';

$lang->instance->actionList = array();
$lang->instance->actionList['install']                = '安装了%s';
$lang->instance->actionList['uninstall']              = '卸载了%s';
$lang->instance->actionList['start']                  = '启动了%s';
$lang->instance->actionList['stop']                   = '关闭了%s';
$lang->instance->actionList['editname']               = '修改了名称';
$lang->instance->actionList['upgrade']                = '升级了%s';
$lang->instance->actionList['backup']                 = '备份了%s';
$lang->instance->actionList['restore']                = '回滚了%s';
$lang->instance->actionList['adjustmemory']           = '调整 %s 的内存到 %s';
$lang->instance->actionList['enableldap']             = '启用了LDAP';
$lang->instance->actionList['disableldap']            = '禁用了LDAP';
$lang->instance->actionList['enablesmtp']             = '启用了SMTP';
$lang->instance->actionList['disablesmtp']            = '禁用了SMTP';
$lang->instance->actionList['updatecustom']           = '修改了自定义配置';
$lang->instance->actionList['tosenior']               = '升级服务到高级版';
$lang->instance->actionList['saveautobackupsettings'] = '修改了自动备份设置';
$lang->instance->actionList['autobackup']             = '系统执行了自动备份';
$lang->instance->actionList['deleteexpiredbackup']    = '系统删除了过期的自动备份';

$lang->instance->sourceList = array();
$lang->instance->sourceList['cloud'] = '渠成公共市场';
$lang->instance->sourceList['local'] = '本地市场';

$lang->instance->channelList = array();
$lang->instance->channelList['test']   = '测试版';
$lang->instance->channelList['stable'] = '稳定版';

$lang->instance->statusList = array();
$lang->instance->statusList['installationFail'] = '安装失败';
$lang->instance->statusList['creating']         = '创建中';
$lang->instance->statusList['initializing']     = '初始化';
$lang->instance->statusList['pulling']          = '下载中';
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
$lang->instance->statusList['upgrading']        = '更新中';
$lang->instance->statusList['unknown']          = '未知';

$lang->instance->htmlStatuses = array();
$lang->instance->htmlStatuses['running']          = "<span class='label label-info label-outline'>%s</span>";
$lang->instance->htmlStatuses['stopped']          = "<span class='label label-default label-outline'>%s</span>";
$lang->instance->htmlStatuses['abnormal']         = "<span class='label label-danger label-outline'>%s</span>";
$lang->instance->htmlStatuses['installationFail'] = $lang->instance->htmlStatuses['abnormal'];
$lang->instance->htmlStatuses['busy']             = "<span class='label label-warning label-success label-outline'>%s</span>";

$lang->instance->memOptions = array();
$lang->instance->memOptions[128 * 1024]   = '128MB';
$lang->instance->memOptions[256 * 1024]   = '256MB';
$lang->instance->memOptions[512 * 1024]   = '512MB';
$lang->instance->memOptions[1024 * 1024]  = '1GB';
$lang->instance->memOptions[2048 * 1024]  = '2GB';
$lang->instance->memOptions[4096 * 1024]  = '4GB';
$lang->instance->memOptions[8192 * 1024]  = '8GB';
$lang->instance->memOptions[16384 * 1024] = '16GB';
$lang->instance->memOptions[32768 * 1024] = '32GB';

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
$lang->instance->notices['success']            = '成功';
$lang->instance->notices['fail']               = '失败';
$lang->instance->notices['error']              = '错误';
$lang->instance->notices['confirmStart']       = '确定启动该应用吗？';
$lang->instance->notices['confirmStop']        = '确定关闭该应用吗？';
$lang->instance->notices['confirmUninstall']   = '确定卸载该应用吗？';
$lang->instance->notices['startSuccess']       = '启动成功';
$lang->instance->notices['startFail']          = '启动失败';
$lang->instance->notices['stopSuccess']        = '关闭成功';
$lang->instance->notices['stopFail']           = '关闭失败';
$lang->instance->notices['uninstallSuccess']   = '卸载成功';
$lang->instance->notices['uninstallFail']      = '卸载失败';
$lang->instance->notices['installSuccess']     = '安装成功';
$lang->instance->notices['installFail']        = '安装失败';
$lang->instance->notices['upgradeSuccess']     = '升级成功';
$lang->instance->notices['upgradeFail']        = '升级失败';
$lang->instance->notices['backupSuccess']      = '备份任务已提交';
$lang->instance->notices['backupFail']         = '备份失败';
$lang->instance->notices['restoreSuccess']     = '回滚任务已提交';
$lang->instance->notices['restoreFail']        = '回滚失败';
$lang->instance->notices['deleteSuccess']      = '删除成功';
$lang->instance->notices['deleteFail']         = '删除失败';
$lang->instance->notices['starting']           = '启动中，请稍候...';
$lang->instance->notices['stopping']           = '关闭中，请稍候...';
$lang->instance->notices['installing']         = '安装中，请稍候...';
$lang->instance->notices['uninstalling']       = '卸载中，请稍候...';
$lang->instance->notices['upgrading']          = '升级中，请稍候...';
$lang->instance->notices['backuping']          = '备份中，请稍候...';
$lang->instance->notices['restoring']          = '回滚中，请稍候...';
$lang->instance->notices['deleting']           = '删除中，请稍候...';
$lang->instance->notices['adjusting']          = '调整中，请稍候...';
$lang->instance->notices['switching']          = '提交中，请稍候...';
$lang->instance->notices['setting']            = '提交中，请稍候...';
$lang->instance->notices['confirmInstall']     = '确定要安装(%s)?';
$lang->instance->notices['confirmUpgrade']     = '确定要升级到最新版吗?';
$lang->instance->notices['confirmBackup']      = '确定要备份吗？';
$lang->instance->notices['confirmRestore']     = '本操作将用备份的数据覆盖当前的数据，确定要回滚吗？';
$lang->instance->notices['confirmDelete']      = '确定要删除该备份数据吗？';
$lang->instance->notices['adjustMemory']       = '确定要调整中内存吗？';
$lang->instance->notices['switchLDAP']         = '修改LDAP会重启服务，确定要修改LDAP吗？';
$lang->instance->notices['enableLDAPFailed']   = '启用LDAP失败';
$lang->instance->notices['disableLDAPFailed']  = '禁用LDAP失败';
$lang->instance->notices['enableLDAPSuccess']  = '启用LDAP成功';
$lang->instance->notices['disableLDAPSuccess'] = '禁用LDAP成功';
$lang->instance->notices['switchSMTP']         = '修改SMTP会重启服务，确定要修改SMTP吗？';
$lang->instance->notices['enableSMTPFailed']   = '启用SMTP失败';
$lang->instance->notices['disableSMTPFailed']  = '禁用SMTP失败';
$lang->instance->notices['enableSMTPSuccess']  = '启用SMTP成功';
$lang->instance->notices['disableSMTPSuccess'] = '禁用SMTP成功';
$lang->instance->notices['confirmCustom']      = '修改自定义配置后服务将自动重启以使配置生效。';
$lang->instance->notices['required']           = '不能为空';

$lang->instance->nameChangeTo      = ' %s 修改为 %s  。';
$lang->instance->versionChangeTo   = ' %s 升级为 %s  。';
$lang->instance->toSeniorSerial    = '从 %s 升级到 %s 。';
$lang->instance->adjustMemorySize  = '建议调整内存到 %s 。';
$lang->instance->enableAutoBackup  = '开启自动备份';
$lang->instance->disableAutoBackup = '关闭自动备份';

$lang->instance->instanceNotExists  = '服务不存在';
$lang->instance->caplicasTooSmall   = '副本数不能小于1';
$lang->instance->empty              = '暂无服务';
$lang->instance->noComponent        = '无组件，点击';
$lang->instance->noHigherVersion    = '未找到更高版本！';
$lang->instance->backupOnlyRunning  = '运行状态才能备份';
$lang->instance->restoreOnlyRunning = '运行状态才能回滚';
$lang->instance->howToSelectDB      = '如何选择?';
$lang->instance->appLifeTip         = 'demo账号安装的应用有30分钟限制，30分钟后自动删除。';
$lang->instance->serialDiff         = '查看版本区别';
$lang->instance->descOfSwitchSerial = '您当前使用的是<strong>%s</strong>，想要体验更多高级功能，可升级至%s。';
$lang->instance->toSeniorAttention  = '重要提示';
$lang->instance->toSeniorTips       = "<ul class='text-danger'><li>版本升级后，无法回退到原版本。</li><li>企业版、旗舰版自安装后免费试用6个月。</li><li>开源版升级到企业版或旗舰版后，试用期最大支持3个用户，
    请检查开源版用户数量。超出限制将不可用。</li><li>升级成功后，服务将自动重启。</li><li>为避免造成数据丢失，请您在升级前务必做好数据备份。</li></ul>";

$lang->instance->errors = new stdclass;
$lang->instance->errors->domainLength         = '域名长度必须介于2-20字符之间';
$lang->instance->errors->domainExists         = '域名已被占用，请使用其它域名。';
$lang->instance->errors->wrongDomainCharacter = '域名只能是小写英文字母和数字';
$lang->instance->errors->noAppInfo            = '获取应用数据失败，请稍候重试。';
$lang->instance->errors->notEnoughResource    = '集群资源不足';
$lang->instance->errors->notEnoughMemory      = '%s 应用需要 %s 内存，当前可用内存%s ，还需要 %s，请扩充内存资源或卸载其他服务后重试！';
$lang->instance->errors->restoreRunning       = '当前回滚正在进行中，请等待当前回滚完成。';
$lang->instance->errors->noBackup             = '无备份数据，';
$lang->instance->errors->wrongRequestData     = '提交的数据有误，请刷新页面后重试。';
$lang->instance->errors->noDBList             = '无数据库或不可访问';
$lang->instance->errors->notFoundDB           = '找不到该数据库';
$lang->instance->errors->dbNameIsEmpty        = '数据库名为空';
$lang->instance->errors->failToAdjustMemory   = '调整内存失败';
$lang->instance->errors->switchLDAPFailed     = '修改LDAP设置失败';
$lang->instance->errors->switchSMTPFailed     = '修改SMTP设置失败';
$lang->instance->errors->updateCustomFailed   = '修改自定义配置失败';
$lang->instance->errors->failToSenior         = '升级到高级版失败';
