<?php
$lang->system->systemInfo     = '系统信息';
$lang->system->dbManagement   = '数据库管理';
$lang->system->ldapManagement = 'LDAP';
$lang->system->dbList         = '数据库列表';
$lang->system->dbName         = '名称';
$lang->system->dbStatus       = '状态';
$lang->system->dbType         = '类型';
$lang->system->action         = '操作';
$lang->system->management     = '管理';
$lang->system->visit          = '访问';
$lang->system->close          = '关闭';
$lang->system->installLDAP    = '安装LDAP';
$lang->system->editLDAP       = '编辑';
$lang->system->LDAPInfo       = 'LDAP信息';
$lang->system->advance        = '高级';
$lang->system->verify         = '校验';

/* LDAP */
$lang->system->ldapEnabled  = '启用LDAP';
$lang->system->ldapQucheng  = '渠成内置';
$lang->system->ldapSource   = '来源';
$lang->system->ldapInstall  = '安装并启用';
$lang->system->ldapUpdate   = '更新';
$lang->system->accountInfo  = '账号信息';
$lang->system->account      = '账号';
$lang->system->password     = '密码';
$lang->system->ldapUsername = '用户名';
$lang->system->ldapName     = '名称';
$lang->system->host         = '主机';
$lang->system->port         = '端口';
$lang->system->account      = '账号';
$lang->system->password     = '密码';
$lang->system->ldapRoot     = '根节点';
$lang->system->filterUser   = '用户过滤';
$lang->system->email        = '邮件字段';
$lang->system->extraAccount = '用户名字段';
$lang->system->ldapAdvance  = '高级设置';
$lang->system->copy         = '复制';
$lang->system->copySuccess  = '已复制到剪切板';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = '渠成内置';
$lang->system->ldapTypeList['extra']   = '外部映射';

/* OSS */
$lang->system->oss = new stdclass;
$lang->system->oss->common    = '对象存储';
$lang->system->oss->user      = '用户名';
$lang->system->oss->password  = '密码';
$lang->system->oss->manage    = '管理';
$lang->system->oss->apiURL    = 'API地址';
$lang->system->oss->accessKey = 'Access Key';
$lang->system->oss->secretKey = 'Secret Key';

/* SMTP */
$lang->system->SMTP = new stdclass;
$lang->system->SMTP->common   = '邮箱配置';
$lang->system->SMTP->enabled  = '启用SMTP';
$lang->system->SMTP->install  = '安装';
$lang->system->SMTP->update   = '更新';
$lang->system->SMTP->edit     = '编辑';
$lang->system->SMTP->editSMTP = '编辑SMTP';
$lang->system->SMTP->account  = '发信邮箱';
$lang->system->SMTP->password = '密码';
$lang->system->SMTP->host     = 'SMTP服务器';
$lang->system->SMTP->port     = 'SMTP端口';
$lang->system->SMTP->save     = '保存';

/* Domain */
$lang->system->customDomain = '新域名';
$lang->system->publicKey    = '公钥';
$lang->system->privateKey   = '私钥';

$lang->system->domain = new stdclass;
$lang->system->domain->common        = '域名管理';
$lang->system->domain->editDomain    = '修改域名配置';
$lang->system->domain->config        = '配置域名和证书';
$lang->system->domain->currentDomain = '当前域名';
$lang->system->domain->oldDomain     = '旧域名';
$lang->system->domain->newDomain     = '新域名';
$lang->system->domain->publicKey     = '公钥';
$lang->system->domain->privateKey    = '私钥';
$lang->system->domain->uploadCert    = '上传证书（仅支持泛域名证书）';

$lang->system->domain->notReuseOldDomain     = '使用自定义域名后无法改回默认域名';
$lang->system->domain->setDNS                = '建议修改域名前请先进行DNS解析，';
$lang->system->domain->dnsHelperLink         = '点击查看帮助文档';
$lang->system->domain->updateInstancesDomain = '更新已安装服务的域名';
$lang->system->domain->totalOldDomain        = '共 %s 个。';
$lang->system->domain->updatingProgress      = '更新中...，剩余 %s 个,';
$lang->system->domain->updating              = '更新中...';

$lang->system->notices = new stdclass;
$lang->system->notices->success               = '成功';
$lang->system->notices->fail                  = '失败';
$lang->system->notices->attention             = '注意';
$lang->system->notices->noLDAP                = '找不到LDAP配置数据';
$lang->system->notices->ldapUsed              = '已经有服务关联了LDAP';
$lang->system->notices->ldapInstallSuccess    = 'LDAP安装成功';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->verifyLDAPSuccess     = '校验LDAP成功！';
$lang->system->notices->fillAllRequiredFields = '请填写全部必填项！';
$lang->system->notices->smtpInstallSuccess    = 'LDAP安装成功';
$lang->system->notices->smtpUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->smtpWhiteList         = '为防止邮件被屏蔽，请在邮件服务器里面将发信邮箱设为白名单';
$lang->system->notices->smtpAuthCode          = '有些邮箱要填写单独申请的授权码，具体请到邮箱相关设置查询';
$lang->system->notices->smtpUsed              = '已经有服务关联了SMTP';
$lang->system->notices->verifySMTPSuccess     = '校验成功！';
$lang->system->notices->pleaseCheckSMTPInfo   = '校验失败！请检查用户名和密码是否正确';
$lang->system->notices->confirmUpdateDomain   = '修改域名后，会自动更新已安装服务的域名，确定要修改吗？';
$lang->system->notices->updateDomainSuccess   = '域名修改成功。';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB                 = '找不到该数据库';
$lang->system->errors->notFoundLDAP               = '找不到LDAP数据';
$lang->system->errors->dbNameIsEmpty              = '数据库名为空';
$lang->system->errors->notSupportedLDAP           = '暂不支持该类型的LDAP';
$lang->system->errors->failToInstallLDAP          = '安装内置LDAP失败';
$lang->system->errors->failToInstallExtraLDAP     = '对接外部LDAP失败';
$lang->system->errors->failToUpdateExtraLDAP      = '更新外部LDAP失败';
$lang->system->errors->failToUninstallQuChengLDAP = '卸载渠成内部LDAP失败';
$lang->system->errors->failToUninstallExtraLDAP   = '卸载外部LDAP失败';
$lang->system->errors->failToDeleteLDAPSnippet    = '删除LDAP片段失败';
$lang->system->errors->verifyLDAPFailed           = '校验LDAP失败';
$lang->system->errors->LDAPLinked                 = '有服务已经关联了LDAP';
$lang->system->errors->SMTPLinked                 = '有服务已经关联了SMTP服务';
$lang->system->errors->failGetOssAccount          = '获取对象存储账号失败';
$lang->system->errors->failToInstallSMTP          = '安装SMTP失败';
$lang->system->errors->failToUninstallSMTP        = '卸载SMTP失败';
$lang->system->errors->verifySMTPFailed           = '校验SMTP失败';
$lang->system->errors->notFoundSMTPApp            = '找不到SMTP代理应用';
$lang->system->errors->notFoundSMTPService        = '找不到SMTP代理服务';
$lang->system->errors->domainIsRequired           = '必须填写域名';
$lang->system->errors->invalidDomain              = '无效的域名或格式错误。域名只允许小写字母、数字、点(.)和中横线(-)';
$lang->system->errors->failToUpdateDomain         = '更新域名失败';
$lang->system->errors->forbiddenOriginalDomain    = '不能修改为平台默认域名';
$lang->system->errors->newDomainIsSameWithOld     = '新域名不能与原域名相同';
