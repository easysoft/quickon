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

$lang->system->ldapEnabled  = '启用LDAP';
$lang->system->ldapQucheng  = '渠成内置';
$lang->system->ldapSource   = '来源';
$lang->system->ldapUsername = '用户名';
$lang->system->ldapRoot     = '根节点';
$lang->system->ldapInstall  = '安装并启用';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = '渠成内置';

$lang->system->notices = new stdclass;
$lang->system->notices->noLDAP             = '找不到LDAP配置数据';
$lang->system->notices->ldapInstallSuccess = 'LDAP安装成功';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB       = '找不到该数据库';
$lang->system->errors->notFoundLDAP     = '找不到LDAP数据';
$lang->system->errors->dbNameIsEmpty    = '数据库名为空';
$lang->system->errors->notSupportedLDAP = '暂不支持该类型的LDAP';
