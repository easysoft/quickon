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
$lang->system->editLDAP       = '编辑';
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

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = '渠成内置';
$lang->system->ldapTypeList['extra']   = '外部映射';

$lang->system->notices = new stdclass;
$lang->system->notices->noLDAP             = '找不到LDAP配置数据';
$lang->system->notices->ldapUsed           = '已经有服务关联了LDAP';
$lang->system->notices->ldapInstallSuccess = 'LDAP安装成功';
$lang->system->notices->ldapUpdateSuccess  = 'LDAP安装成功';
$lang->system->notices->verifyLDAPSuccess  = '校验LDAP成功！';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB             = '找不到该数据库';
$lang->system->errors->notFoundLDAP           = '找不到LDAP数据';
$lang->system->errors->dbNameIsEmpty          = '数据库名为空';
$lang->system->errors->notSupportedLDAP       = '暂不支持该类型的LDAP';
$lang->system->errors->failToInstallLDAP      = '安装内置LDAP失败';
$lang->system->errors->failToInstallExtraLDAP = '安装外部LDAP失败';
$lang->system->errors->failToUpdateExtraLDAP  = '更新外部LDAP失败';
$lang->system->errors->verifyLDAPFailed       = '校验LDAP失败';
