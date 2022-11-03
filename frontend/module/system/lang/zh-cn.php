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
$lang->system->connectLdap  = '校验';
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

$lang->system->notices = new stdclass;
$lang->system->notices->attention             = '注意';
$lang->system->notices->noLDAP                = '找不到LDAP配置数据';
$lang->system->notices->ldapUsed              = '已经有服务关联了LDAP';
$lang->system->notices->ldapInstallSuccess    = 'LDAP安装成功';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->verifyLDAPSuccess     = '校验LDAP成功！';
$lang->system->notices->fillAllRequiredFields = '请填写全部必填项！';

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
$lang->system->errors->failGetOssAccount          = '获取对象存储账号失败';
