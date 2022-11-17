<?php
/**
 * The model file of app instance module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycrop.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class InstanceModel extends model
{
    /**
     * Construct method: load CNE model, and set primaryDomain.
     *
     * @access public
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('cne');
    }

    /**
     * Get by id.
     *
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getByID($id)
    {
        $deadline = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));

        $instance = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->eq($id)
            ->andWhere('deleted')->eq(0)
            ->andWhere('createdBy')->eq($this->app->user->account)
            ->beginIF(commonModel::isDemoAccount())->andWhere('createdAt')->gt($deadline)->fi()
            ->fetch();
        if(!$instance) return null;

        $instance->spaceData = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($instance->space)->fetch();

        return $instance;
    }

    /**
     * Get by id list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        $deadline = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));

        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->beginIF(commonModel::isDemoAccount())->andWhere('createdAt')->gt($deadline)->fi()
            ->fetchAll('id');
        $spaces    = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->in(array_column($instances, 'space'))->fetchAll('id');
        foreach($instances as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        return $instances;
    }

    /**
     * Get instances list by account.
     *
     * @param  string $account
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAccount($account = '', $pager = null, $pinned = '', $searchParam = '', $status = 'all')
    {
        $deadline = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));

        $defaultSpace = $this->loadModel('space')->defaultSpace($account ? $account : $this->app->user->account);

        $instances = $this->dao->select('instance.*')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            ->andWhere('space.id')->eq($defaultSpace->id)
            ->beginIF($account)->andWhere('space.owner')->eq($account)->fi()
            ->beginIF($pinned)->andWhere('instance.pinned')->eq((int)$pinned)->fi()
            ->beginIF($searchParam)->andWhere('instance.name')->like("%{$searchParam}%")->fi()
            ->beginIF($status != 'all')->andWhere('instance.status')->eq($status)->fi()
            ->beginIF(commonModel::isDemoAccount())->andWhere('instance.createdAt')->gt($deadline)->fi()
            ->orderBy('instance.id desc')
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');

        $spaces = $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in(array_column($instances, 'space'))
            ->fetchAll('id');

        foreach($instances as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        return $instances;
    }

    /**
     * Count instance which is enabled LDAP.
     *
     * @access public
     * @return int
     */
    public function countLDAP()
    {
        $count = $this->dao->select('count(*) as ldapQty')->from(TABLE_INSTANCE)->where('deleted')->eq(0)->andWhere('length(ldapSnippetName) > 0')->fetch();
        return $count->ldapQty;
    }

    /**
     * Get quantity of total installed services.
     *
     * @access public
     * @return int
     */
    public function totalServices()
    {
        $deadline = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));

        $defaultSpace = $this->loadModel('space')->defaultSpace($this->app->user->account);

        $count = $this->dao->select('count(*) as qty')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            ->andWhere('space.id')->eq($defaultSpace->id)
            ->beginIF(commonModel::isDemoAccount())->andWhere('instance.createdAt')->gt($deadline)->fi()
            ->fetch();

        return $count->qty;
    }

    /**
     * Pin instance to navigation page or Unpin instance.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function pinToggle($instanceID)
    {
        $instance = $this->getByID($instanceID);
        $pinned = $instance->pinned == '0' ? '1' : '0';
        $this->dao->update(TABLE_INSTANCE)->set('pinned')->eq($pinned)->where('id')->eq($instanceID)->exec();
    }

    /**
     * Switch LDAP between enable and disable.
     *
     * @param  object $instance
     * @param  bool   $enableLDAP
     * @access public
     * @return bool
     */
    public function switchLDAP($instance, $enableLDAP)
    {
        $this->loadModel('system');
        $snippetName = $this->system->ldapSnippetName();

        $settings = new stdclass;
        if($enableLDAP)
        {
            $settings->settings_snippets = [$snippetName];
        }
        else
        {
            $settings->settings_snippets = [$snippetName . '-'];
        }

        $success = $this->cne->updateConfig($instance, $settings);
        if(!$success)
        {
            dao::$errors[] = $this->lang->instance->errors->failToAdjustMemory;
            return false;
        }

        if($enableLDAP)
        {
            $this->dao->update(TABLE_INSTANCE)->set('ldapSnippetName')->eq($snippetName)->where('id')->eq($instance->id)->exec();
            $this->loadModel('action')->create('instance', $instance->id, 'enableLDAP');
        }
        else
        {
            $this->dao->update(TABLE_INSTANCE)->set('ldapSnippetName')->eq('')->where('id')->eq($instance->id)->exec();
            $this->loadModel('action')->create('instance', $instance->id, 'disableLDAP');
        }

        return true;
    }

    /**
     * Update instance memory size.
     *
     * @param  object $instnace
     * @param  int    $size
     * @access public
     * @return bool
     */
    public function updateMemorySize($instnace, $size = '')
    {
        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->resources = new stdclass;
        $settings->settings_map->resources->memory = $size;

        $success = $this->cne->updateConfig($instnace, $settings);
        if($success)
        {
            $this->loadModel('action')->create('instance', $instnace->id, 'adjustMemory', helper::formatKB(intval($size / 1024)));
            return true;
        }

        dao::$errors[] = $this->lang->instance->errors->failToAdjustMemory;
        return false;
    }

    /**
     * Update instance status.
     *
     * @param  int    $int
     * @param  string $status
     * @access public
     * @return int
     */
    public function updateStatus($id, $status)
    {
        $instanceData = new stdclass;
        $instanceData->status = trim($status);
        return $this->updateByID($id, $instanceData);
    }

    /**
     * Update instance by id.
     *
     * @param  int    $id
     * @param  object $newInstance
     * @access public
     * @return void
     */
    public function updateByID($id, $newInstance)
    {
        return $this->dao->update(TABLE_INSTANCE)->data($newInstance)
            ->autoCheck()
            ->checkIF(isset($newInstance->name), 'name', 'notempty')
            ->checkIF(isset($newInstance->status), 'status', 'in', array_keys($this->lang->instance->statusList))
            ->where('id')->eq($id)->exec();
    }


    /**
     * Soft delete instance by id.
     *
     * @param  int    $id
     * @access public
     * @return mixed
     */
    public function softDeleteByID($id)
    {
        return $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
    }

    /**
     * If actions are allowed to do.
     *
     * @param  string $action
     * @param  object $instance
     * @access public
     * @return boolean
     */
    public function canDo($action, $instance)
    {
        $busy = in_array($instance->status, array('creating', 'initializing', 'starting', 'stopping', 'suspending', 'pulling', 'destroying'));
        switch($action)
        {
            case 'start':
                return !($busy || in_array($instance->status, array('running', 'abnormal', 'destroyed')));
            case 'stop':
                return !($busy || in_array($instance->status, array('stopped', 'installationFail')));
            case 'uninstall':
                return !in_array($instance->status, array('destroying'));
            case 'visit':
                return $instance->status == 'running';
            default:
                return false;
        }
    }

    /**
     * Create third domain.
     *
     * @param  int    $length
     * @param  int    $triedTimes
     * @access public
     * @return mixed
     */
    public function randThirdDomain($length = 4, $triedTimes = 0)
    {
        if($triedTimes > 16) $length++;

        $thirdDomain = strtolower(helper::randStr($length));
        if(!$this->domainExists($thirdDomain)) return $thirdDomain;

        return $this->randThirdDomain($length, $triedTimes++);
    }

    /**
     * Return full domain.
     *
     * @param  string $thirdDomain
     * @access public
     * @return mixed
     */
    public function fullDomain($thirdDomain)
    {
        return $thirdDomain . '.' . $this->config->CNE->app->domain;
    }

    /**
     * Check if the domain exists.
     *
     * @param  string $thirdDomain
     * @access public
     * @return bool   true: exists, false: not exist.
     */
    public function domainExists($thirdDomain)
    {
        $domain = $this->fullDomain($thirdDomain);
        return boolval($this->dao->select('id')->from(TABLE_INSTANCE)->where('domain')->eq($domain)->andWhere('deleted')->eq(0)->fetch());
    }

    /**
     * Check if the k8name exists.
     *
     * @param  string $k8name
     * @access public
     * @return bool   true: exists, false: not exist.
     */
    public function k8nameExists($k8name)
    {
        return boolval($this->dao->select('id')->from(TABLE_INSTANCE)->where('k8name')->eq($k8name)->andWhere('deleted')->eq(0)->fetch());
    }

    /**
     * Mount installation settings by custom data.
     *
     * @param  object  $customData
     * @param  object  $dbInfo
     * @param  object  $app
     * @param  object  $instance
     * @access private
     * @return object
     */
    private function installationSettingsMap($customData, $dbInfo, $app, $instance)
    {
        $settingsMap = new stdclass;
        if($customData->customDomain)
        {
            $settingsMap->ingress = new stdclass;
            $settingsMap->ingress->enabled = true;
            $settingsMap->ingress->host    = $this->fullDomain($customData->customDomain);

            $settingsMap->global = new stdclass;
            $settingsMap->global->ingress = new stdclass;
            $settingsMap->global->ingress->enabled = true;
            $settingsMap->global->ingress->host    = $settingsMap->ingress->host;
        }

        if(empty($customData->dbType) || $customData->dbType == 'unsharedDB' || empty($customData->dbService)) return $settingsMap;

        /* Set DB settings. */
        $dbSettings = new stdclass;
        $dbSettings->service   = $dbInfo->name;
        $dbSettings->namespace = $dbInfo->namespace;
        $dbSettings->host      = $dbInfo->host;
        $dbSettings->port      = $dbInfo->port;
        $dbSettings->name      = 'db_' . $instance->id;
        $dbSettings->user      = 'user_' . $instance->id;

        $dbSettings = $this->getValidDBSettings($dbSettings, $dbSettings->user, $dbSettings->name);

        $settingsMap->mysql = new stdclass;
        $settingsMap->mysql->enabled = false;

        $settingsMap->mysql->auth = new stdclass;
        $settingsMap->mysql->auth->user     = $dbSettings->user;
        $settingsMap->mysql->auth->password = helper::randStr(12);
        $settingsMap->mysql->auth->host     = $dbSettings->host;
        $settingsMap->mysql->auth->port     = $dbSettings->port;
        $settingsMap->mysql->auth->database = $dbSettings->name;

        $settingsMap->mysql->auth->dbservice = new stdclass;
        $settingsMap->mysql->auth->dbservice->name      = $dbSettings->service;
        $settingsMap->mysql->auth->dbservice->namespace = $dbSettings->namespace;

        return $settingsMap;
    }

    /**
     * Return valid DBSettings.
     *
     * @param  object  $dbSettings
     * @param  string  $defaultUser
     * @param  string  $defaultDBName
     * @param  int     $times
     * @access private
     * @return null|object
     */
    private function getValidDBSettings($dbSettings, $defaultUser, $defaultDBName, $times = 1)
    {
        if($times >10) return;

        $validatedResult = $this->cne->validateDB($dbSettings->service, $dbSettings->name, $dbSettings->user, $dbSettings->namespace);
        if($validatedResult->user && $validatedResult->database) return $dbSettings;

        if(!$validatedResult->user)     $dbSettings->user = $defaultUser . '_' . help::randStr(4);
        if(!$validatedResult->database) $dbSettings->database = $defaultDBName  . '_' . help::randStr(4);

        return $this->solveDBSettings($dbSettings, $defaultUser, $defaultDBName, $times++);
    }

    /**
     * Install app by request from Web page.
     *
     * @param  object $app
     * @param  object $dbInfo
     * @param  object $customData
     * @param  int    $spaceID
     * @access public
     * @return false|object Failure: return false, Success: return instance
     */
    public function install($app, $dbInfo, $customData, $spaceID = null)
    {
        $this->loadModel('space');
        if($spaceID)
        {
            $space = $this->space->getByID($spaceID);
        }
        else
        {
            $space = $this->space->defaultSpace($this->app->user->account);
        }

        $ldapSnippet = isset($customData->snippets[0]) ? $customData->snippets[0] : null;
        $channel     = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $instance    = $this->createInstance($app, $space, $customData->customDomain, $customData->customName, '', $channel, $ldapSnippet);

        if(!$instance) return false;

        $settingMap = $this->installationSettingsMap($customData, $dbInfo, $app, $instance);
        $snippets   = zget($customData, 'snippets', array());
        return $this->doCneInstall($app, $instance, $space, $settingMap, $snippets);
    }

    /**
     * Install LDAP.
     *
     * @param  object $app
     * @param  string $thirdDomain
     * @param  string $instanceName
     * @param  string $channel
     * @access public
     * @return bool|object
     */
    public function installLDAP($app, $thirdDomain = '', $instanceName = '', $k8name = '', $channel = 'stable')
    {
        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $customData = new stdclass;
        $customData->dbType       = null;
        $customData->customDomain = $thirdDomain ? $thirdDomain : $this->randThirdDomain();

        $instance = $this->createInstance($app, $space, $customData->customDomain, $instanceName, $k8name, $channel);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        $settingMap = $this->installationSettingsMap($customData, array(), $app, $instance);

        $settingMap->auth = new stdclass;
        $settingMap->auth->username = $app->account ? $app->account->username : 'admin';
        $settingMap->auth->password = helper::randStr(16);
        $settingMap->auth->root     = 'dc=quickon,dc=org';

        $instance = $this->doCneInstall($app, $instance, $space, $settingMap);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        /* Post snippet to CNE. */
        $snippetSettings = new stdclass;
        $snippetSettings->name        = 'snippet-qucheng-ldap';
        $snippetSettings->namespace   = $space->k8space;
        $snippetSettings->auto_import = false;

        $snippetSettings->values = new stdclass;
        $snippetSettings->values->auth = new stdclass;
        $snippetSettings->values->auth->ldap = new stdclass;
        $snippetSettings->values->auth->ldap->enabled   = true;
        $snippetSettings->values->auth->ldap->type      = 'ldap';
        $snippetSettings->values->auth->ldap->host      = "{$instance->k8name}.{$snippetSettings->namespace}.svc";
        $snippetSettings->values->auth->ldap->port      = '1389';
        $snippetSettings->values->auth->ldap->bindDN    = "cn={$settingMap->auth->username},dc=quickon,dc=org";
        $snippetSettings->values->auth->ldap->bindPass  = $settingMap->auth->password;
        $snippetSettings->values->auth->ldap->baseDN    = $settingMap->auth->root;
        $snippetSettings->values->auth->ldap->filter    = "&(objectClass=posixAccount)(cn=%s)";
        $snippetSettings->values->auth->ldap->attrUser  = 'uid';
        $snippetSettings->values->auth->ldap->attrEmail = 'mail';

        $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
        if($snippetResult->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            $this->uninstall($instance);
            return false;
        }

        /* Save LDAP account. */
        $secretKey = helper::readKey();
        $settingMap->auth->password = openssl_encrypt($settingMap->auth->password, 'DES-ECB', $secretKey);
        $this->dao->update(TABLE_INSTANCE)->set('ldapSettings')->eq(json_encode($settingMap))->where('id')->eq($instance->id)->exec();
        $this->loadModel('setting')->setItem('system.common.ldap.active', 'qucheng');
        $this->loadModel('setting')->setItem('system.common.ldap.instanceID', $instance->id);
        $this->loadModel('setting')->setItem('system.common.ldap.snippetName', $snippetSettings->name); // Parameter for App installation API.

        return $instance;
    }

    /**
     * Install app through API.
     *
     * @param  object $app
     * @param  string $thirdDomain
     * @param  string $name
     * @param  string $channel
     * @access public
     * @return bool|object
     */
    public function apiInstall($app, $thirdDomain = '', $name = '', $k8name = '', $channel = 'stable')
    {
        $this->loadModel('space');
        $space = $this->space->defaultSpace($this->app->user->account);

        $customData = new stdclass;
        $customData->dbType       = null;
        $customData->customDomain = $thirdDomain ? $thirdDomain : $this->randThirdDomain();

        $dbInfo = new stdclass;
        $dbList = $this->cne->sharedDBList();
        if(count($dbList) > 0)
        {
            $dbInfo = reset($dbList);

            $customData->dbType    = 'sharedDB';
            $customData->dbService = $dbInfo->name; // Use first shared database.
        }

        $instance = $this->createInstance($app, $space, $customData->customDomain, $name, $k8name, $channel);
        if(!$instance) return false;

        $settingMap = $this->installationSettingsMap($customData, $dbInfo, $app, $instance);
        return $this->doCneInstall($app, $instance, $space, $settingMap);
    }

    /**
     * Create instance recorder for installation.
     *
     * @param  object $app
     * @param  object $space
     * @param  object $thirdDomain
     * @param  string $name
     * @param  string $channel
     * @access public
     * @return bool|object
     */
    public function createInstance($app, $space, $thirdDomain, $name = '', $k8name = '', $channel = 'stable', $ldapSnippet = null)
    {
        if(empty($k8name)) $k8name = "{$app->chart}-{$this->app->user->account}-" . date('YmdHis'); //name rule: chartName-userAccount-YmdHis;

        $instanceData = new stdclass;
        $instanceData->appId           = $app->id;
        $instanceData->appName         = $app->alias;
        $instanceData->name            = !empty($name)   ? $name : $app->alias;
        $instanceData->domain          = !empty($thirdDomain) ? $this->fullDomain($thirdDomain) : '';
        $instanceData->logo            = $app->logo;
        $instanceData->desc            = $app->desc;
        $instanceData->introduction    = isset($app->introduction) ? $app->introduction : $app->desc;
        $instanceData->source          = 'cloud';
        $instanceData->channel         = $channel;
        $instanceData->chart           = $app->chart;
        $instanceData->appVersion      = $app->app_version;
        $instanceData->version         = $app->version;
        $instanceData->space           = $space->id;
        $instanceData->k8name          = $k8name;
        $instanceData->status          = 'creating';
        $instanceData->createdBy       = $this->app->user->account;
        $instanceData->createdAt       = date('Y-m-d H:i:s');

        if($ldapSnippet) $instanceData->ldapSnippetName = $ldapSnippet;

        $this->dao->insert(TABLE_INSTANCE)->data($instanceData)->autoCheck()->exec();
        if(dao::isError()) return false;

        $instance = $this->getByID($this->dao->lastInsertID());
        return $instance;
    }

    /**
     * Create app instance on CNE platform.
     *
     * @param  object $app
     * @param  object $instance
     * @param  object $space
     * @param  object $settingMap
     * @param  array  $snippets
     * @access private
     * @return object|bool
     */
    private function doCneInstall($app, $instance, $space, $settingMap, $snippets = array())
    {
        $apiParams = new stdclass;
        $apiParams->userame           = $instance->createdBy;
        $apiParams->cluser            = '';
        $apiParams->namespace         = $space->k8space;
        $apiParams->name              = $instance->k8name;
        $apiParams->chart             = $instance->chart;
        $apiParams->version           = $instance->version;
        $apiParams->channel           = $instance->channel;
        $apiParams->settings_map      = $settingMap;
        $apiParams->settings_snippets = $snippets;

        if(strtolower($this->config->CNE->app->domain) == 'demo.haogs.cn') $apiParams->settings_snippets = array('quickon_saas');

        $result = $this->cne->installApp($apiParams);
        if($result->code != 200)
        {
            $this->dao->delete()->from(TABLE_INSTANCE)->where('id')->eq($instance->id)->exec();
            return false;
        }

        $this->loadModel('action')->create('instance', $instance->id, 'install', '', json_encode(array('result' => $result, 'app' => $app)));

        $instance->status     = 'initializing';
        $instance->dbSettings = json_encode($apiParams->settings_map);

        $this->updateByID($instance->id, array('status' => $instance->status, 'dbSettings' => $instance->dbSettings));

        return  $instance;
    }

    /*
     * Uninstall app instance.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function uninstall($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';// Multiple cluster should set this field.
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = $instance->channel;
        $apiParams->namespace = $instance->spaceData->k8space;

        $result = $this->cne->uninstallApp($apiParams);
        if($result->code == 200 || $result->code == 404) $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('id')->eq($instance->id)->exec();

        return $result;
    }

    /*
     * Start app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function start($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;

        $result = $this->cne->startApp($apiParams);
        if($result->code == 200) $this->dao->update(TABLE_INSTANCE)->set('status')->eq('starting')->where('id')->eq($instance->id)->exec();

        return $result;
    }

    /*
     * Stop app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function stop($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';// Mulit cluster should set this field.
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;

        $result = $this->cne->stopApp($apiParams);
        if($result->code == 200) $this->dao->update(TABLE_INSTANCE)->set('status')->eq('stopping')->where('id')->eq($instance->id)->exec();

        return $result;
    }

    /**
     * Upgrade app instance to higher version.
     *
     * @param  object $instance
     * @param  string $toVersion
     * @param  string $appVersion
     * @access public
     * @return bool
     */
    public function upgrade($instance, $toVersion, $appVersion)
    {
        $success = $this->cne->upgradeToVersion($instance, $toVersion);
        if(!$success) return false;

        $instanceData = new stdclass;
        $instanceData->version    = $toVersion;
        $instanceData->appVersion = $appVersion;
        $this->updateByID($instance->id, $instanceData);

        return true;
    }

    /**
     * Upgrade to senior App.
     *
     * @param  object $instance
     * @param  object $seniorApp
     * @access public
     * @return bool
     */
    public function upgradeToSenior($instance, $seniorApp)
    {
        $instance->chart   = $seniorApp->chart;
        $instance->version = $seniorApp->version;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->nameOverride = $this->parseK8Name($instance->k8name)->chart;

        $settings->settings_map->global = new stdclass;
        $settings->settings_map->global->stopped = false;

        if(!$this->cne->updateConfig($instance, $settings))
        {
            dao::$errors[] = $this->lang->instance->errors->failToSenior;
            return false;
        }

        $seniorData = new stdclass;
        $seniorData->chart      = $seniorApp->chart;
        $seniorData->version    = $seniorApp->version;
        $seniorData->appVersion = $seniorApp->app_version;
        $seniorData->appID      = $seniorApp->id;
        $seniorData->appName    = $seniorApp->alias;
        $seniorData->name       = $instance->name . "($seniorApp->alias)";

        $this->dao->update(TABLE_INSTANCE)->data($seniorData)->where('id')->eq($instance->id)->exec();

        $logExtra = new stdclass;
        $logExtra->result = 'success';
        $logExtra->data = new stdclass;
        $logExtra->data->oldAppName = $instance->appName;
        $logExtra->data->newAppName = $seniorApp->alias;

        $this->loadModel('action')->create('instance', $instance->id, 'tosenior', '', json_encode($logExtra));

        return true;
    }

    /*
     * Query and update instances status.
     *
     * @param  array $instances
     * @access public
     * @return array  new status list [{id:xx, status: xx, changed: true/false}]
     */
    public function batchFresh(&$instances)
    {
        $statusList = $this->cne->batchQueryStatus($instances);

        $newStatusList = array();

        foreach($instances as $instance)
        {
            $statusData = zget($statusList, $instance->k8name, '');
            if($statusData)
            {
                if($instance->status != $statusData->status || $instance->version != $statusData->version || $instance->domain != $statusData->access_host)
                {
                    $this->dao->update(TABLE_INSTANCE)
                        ->set('status')->eq($statusData->status)
                        ->beginIF($statusData->version)->set('version')->eq($statusData->version)->fi()
                        ->beginIF($statusData->access_host)->set('domain')->eq($statusData->access_host)->fi()
                        ->where('id')->eq($instance->id)
                        ->autoCheck()
                        ->exec();
                    $instance->status = $statusData->status;
                }
            }

            $status = new stdclass;
            $status->id     = $instance->id;
            $status->status = $instance->status;

            $newStatusList[] = $status;
        }

        return $newStatusList;
    }

    /*
     * Query and update instance status.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function freshStatus($instance)
    {
        $instance->runDuration = 0;
        $statusResponse = $this->cne->queryStatus($instance);
        if($statusResponse->code != 200) return $instance;

        $statusData = $statusResponse->data;
        $instance->runDuration = intval($statusData->age); // Run duration used in view page.

        if($instance->status != $statusData->status || $instance->version != $statusData->version || $instance->domain != $statusData->access_host)
        {
            $this->dao->update(TABLE_INSTANCE)
                ->set('status')->eq($statusData->status)
                ->beginIF($statusData->version)->set('version')->eq($statusData->version)->fi()
                ->beginIF($statusData->access_host)->set('domain')->eq($statusData->access_host)->fi()
                ->where('id')->eq($instance->id)
                ->autoCheck()
                ->exec();
        }

        return $instance;
    }

    /**
     * Backup instance.
     *
     * @param  object $instance
     * @param  object $user
     * @access public
     * @return bool
     */
    public function backup($instance, $user)
    {
        $result = $this->cne->backup($instance, $user->account);
        if($result->code != 200) return false;

        return true;
    }

    /**
     * Restore instance.
     *
     * @param  object $instance
     * @param  object $user
     * @param  string $backupName
     * @access public
     * @return bool
     */
    public function restore($instance, $user, $backupName)
    {
        $result = $this->cne->restore($instance, $backupName, $user->account);
        if($result->code != 200) return false;

        return true;
    }

    /**
     * Get backup list of instance.
     *
     * @param  object $instance
     * @access public
     * @return array
     */
    public function backupList($instance)
    {
        $result = $this->cne->backupList($instance);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $backupList = $result->data;
        usort($backupList, function($backup1, $backup2){ return $backup1->create_time < $backup2->create_time; });

        $accounts = array_column($backupList, 'creator');
        foreach($backupList as $backup) $accounts = array_merge($accounts, array_column($backup->restores, 'creator'));

        $accounts = array_unique($accounts);

        $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');

        foreach($backupList as &$backup)
        {
            $backup->latest_restore_time   = 0;
            $backup->latest_restore_status = '';
            $backup->username = zget($users, $backup->creator);
            /* Mount backup operator info and latest restore info. */
            foreach($backup->restores as &$restore)
            {
                $restore->username = zget($users, $restore->creator);

                if($restore->create_time > $backup->latest_restore_time)
                {
                    $backup->latest_restore_time   = $restore->create_time;
                    $backup->latest_restore_status = $restore->status;
                }
            }
        }

        return $backupList;
    }

    /**
     * Mount DB name and alias to array for select options.
     *
     * @param  array  $databases
     * @access public
     * @return array
     */
    public function dbListToOptions($databases)
    {
        $dbList = array();
        foreach($databases as $database) $dbList[$database->name] = zget($database, 'alias', $database->name);

        return $dbList;
    }

    /**
     * Restore instance by data from k8s cluster.
     *
     * @access public
     * @return void
     */
    public function restoreInstanceList()
    {
        $k8AppList  = $this->cne->instancelist();
        $k8NameList = array_keys($k8AppList);

        //软删除不存在的数据
        $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('k8name')->notIn($k8NameList)->exec();
        foreach($k8AppList as $k8App)
        {
            $existInstance = $this->dao->select('id')->from(TABLE_INSTANCE)->where('k8name')->eq($k8App->name)->fetch();
            if($existInstance) continue;

            $this->loadModel('store');
            $marketApp = $this->store->getAppInfo(0, false, $k8App->chart, $k8App->version,  $k8App->channel);
            if(empty($marketApp)) continue;

            $instanceData = new stdclass;
            $instanceData->appId        = $marketApp->id;
            $instanceData->appName      = $marketApp->alias;
            $instanceData->name         = $marketApp->alias;
            $instanceData->logo         = $marketApp->logo;
            $instanceData->desc         = $marketApp->desc;
            $instanceData->introduction = isset($marketApp->introduction) ? $marketApp->introduction : $marketApp->desc;
            $instanceData->source       = 'cloud';
            $instanceData->channel      = $k8App->channel;
            $instanceData->chart        = $k8App->chart;
            $instanceData->appVersion   = $marketApp->app_version;
            $instanceData->version      = $k8App->version;
            $instanceData->k8name       = $k8App->name;
            $instanceData->status       = 'stopped';

            $parsedK8Name = $this->parseK8Name($k8App->name);

            $instanceData->createdBy = $k8App->username ? $k8App->username : $parsedK8Name->createdBy;
            $instanceData->createdAt =  $parsedK8Name->createdAt;

            $space = $this->dao->select('id,k8space')->from(TABLE_SPACE)->where('k8space')->eq($k8App->namespace)->fetch();
            if(empty($space)) $space = $this->loadModel('space')->defaultSpace($instanceData->createdBy);

            $instanceData->space = $space ? $space->id : $defaultSpace->id;

            $this->dao->insert(TABLE_INSTANCE)->data($instanceData)->exec();
        }
    }

    /**
     * Parse K8Name to get more data: chart, created time, user name.
     *
     * @param  string $k8Name
     * @access public
     * @return object
     */
    public function parseK8Name($k8Name)
    {
        $datePosition = strripos($k8Name, '-');
        $createdAt    = trim(substr($k8Name, $datePosition), '-');

        $createdBy       = trim(substr($k8Name, 0, $datePosition), '-');
        $accountPosition = strripos($createdBy, '-');
        $createdBy       = trim(substr($createdBy, $accountPosition), '-');

        $parsedData = new stdclass;
        $parsedData->chart     = trim(substr($k8Name, 0, $accountPosition));
        $parsedData->createdBy = trim($createdBy, '-');
        $parsedData->createdAt = date('Y-m-d H:i:s', strtotime($createdAt));

        return $parsedData;
    }

    /**
     * Delete expired instances if run in demo mode.
     *
     * @access public
     * @return void
     */
    public function deleteExpiredDemoInstance()
    {
        $deadline     = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));
        $demoAccounts = array_filter(explode(',', $this->config->demoAccounts));

        $instanceList = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('createdAt')->lt($deadline)
            ->andWhere('createdBy')->in($demoAccounts)
            ->fetchAll();
        if(empty($instanceList)) return;

        $spaceList = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->in(array_column($instanceList, 'space'))->fetchAll('id');

        foreach($instanceList as $instance)
        {
            $instance->spaceData = zget($spaceList, $instance->space);
            if(empty($instance->spaceData)) continue;

            $this->uninstall($instance);
        }
    }

    /**
     * Filter memory options that smaller then current memory size.
     *
     * @param  object $resources
     * @access public
     * @return array
     */
    public function filterMemOptions($resources)
    {
        $currentMemory = intval($resources->min->memory / 1024);

        $options = [];
        foreach($this->lang->instance->memOptions as $size => $text)
        {
            if($size > $currentMemory) $options[$size] = $text;
        }

        return $options;
    }

    /**
     * Print suggested memory size by current memory usage. Show suggested messge when memory usage more than 90%.
     *
     * @param  object $memUsage
     * @param  array  $memoryOptions
     * @access public
     * @return void
     */
    public function printSuggestedMemory($memUsage, $memoryOptions)
    {
        if($memUsage->rate < 90) return;

        foreach($memoryOptions as  $size => $memText)
        {
            if($size > ($memUsage->limit / 1024))
            {
                printf($this->lang->instance->adjustMemorySize, $memText);
                return ;
            }
        }
    }

    /**
     * Print instance status.
     *
     * @param  object $instance
     * @param  bool   $showText
     * @access public
     * @return void
     */
    public function printStatus($instance, $showText = true)
    {
        $html = zget($this->lang->instance->htmlStatuses, $instance->status, $this->lang->instance->htmlStatuses['busy']);
        $statusText = zget($this->lang->instance->statusList, $instance->status, '');
        printf($html, $statusText);
    }

    /**
     * Print CPU usage.
     *
     * @param  object $instance
     * @param  object $metrics
     * @param  string $type    'bar' is progress bar, 'pie' is progress pie.
     * @static
     * @access public
     * @return viod
     */
    public static function printCpuUsage($instance, $metrics, $type = 'bar')
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->limit}";

        if(strtolower($type) == 'pie') return commonModel::printProgressPie($rate, '', $tip);

        $valueType = 'percent';
        if($instance->status == 'stopped') $valueType = '';

        return commonModel::printProgressBar($rate, '', $tip, $valueType);
    }

    /**
     * Print memory usage.
     *
     * @param  object $instance
     * @param  object $metrics
     * @param  string $type    'bar' is progress bar, 'pie' is progress pie.
     * @static
     * @access public
     * @return viod
     */
    public static function printMemUsage($instnace, $metrics, $type = 'bar')
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage / 1024) . ' / ' . helper::formatKB($metrics->limit / 1024);

        if(strtolower($type) == 'pie')
        {
            return commonModel::printProgressPie($rate, '', $tip);
        }

        $valueType = 'tip';
        if($instnace->status == 'stopped') $valueType = '';

        return commonModel::printProgressBar($rate, '', $tip, $valueType);
    }

    /*
     * Print action buttons with icon.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printIconActions($instance)
    {
        $actionHtml = '';

        $disableStart = !$this->canDo('start', $instance);
        $actionHtml  .= html::commonButton("<i class='icon-play'></i>", "instance-id='{$instance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn btn-lg btn-action");

        $disableStop = !$this->canDo('stop', $instance);
        $actionHtml .= html::commonButton('<i class="icon-off"></i>', "instance-id='{$instance->id}' title='{$this->lang->instance->stop}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn btn-lg btn-action');

        $disableUninstall = !$this->canDo('uninstall', $instance);
        $actionHtml      .= html::commonButton('<i class="icon-trash"></i>', "instance-id='{$instance->id}' title='{$this->lang->instance->uninstall}'" . ($disableUninstall ? ' disabled ' : ''), 'btn-uninstall btn btn-lg btn-action');

        if($instance->domain)
        {
            $disableVisit = !$this->canDo('visit', $instance);
            $actionHtml  .= html::a('//' . $instance->domain, '<i class="icon icon-menu-my"></i>', '_blank', "title='{$this->lang->instance->visit}' class='btn btn-lg btn-action btn-link'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
        }

        echo $actionHtml;
    }

    /*
     * Print action buttons with text.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printTextActions($instance)
    {
        $actionHtml = '';

        $disableStart = !$this->canDo('start', $instance);
        $actionHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$instance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $disableStop = !$this->canDo('stop', $instance);
        $actionHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$instance->id}' title='{$this->lang->instance->stop}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-warning label-lg');

        $disableUninstall = !$this->canDo('uninstall', $instance);
        $actionHtml      .= html::commonButton($this->lang->instance->uninstall, "instance-id='{$instance->id}' title='{$this->lang->instance->uninstall}'" . ($disableUninstall ? ' disabled ' : ''), 'btn-uninstall btn label  label-outline label-danger label-lg');

        if($instance->domain)
        {
            $disableVisit = !$this->canDo('visit', $instance);
            $actionHtml  .= html::a('//' . $instance->domain, $this->lang->instance->visit, '_blank', "title='{$this->lang->instance->visit}' class='btn btn-primary label-lg'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
        }

        echo $actionHtml;
    }

    /**
     * Print backup button of instance.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printBackupBtn($instance)
    {
        $disabled = $instance->status == 'running' ? '' : 'disabled';
        $title    = empty($disabled) ? $this->lang->instance->backup->create : $this->lang->instance->backupOnlyRunning;
        $btn      = html::commonButton($this->lang->instance->backup->create, "instance-id='{$instance->id}' title='{$title}' {$disabled}", "btn-backup btn btn-primary");

        echo $btn;
    }

    /**
     * Print restore button of instance.
     *
     * @param  object $instance
     * @param  object $backup
     * @access public
     * @return void
     */
    public function printRestoreBtn($instance, $backup)
    {
        $disabled = $instance->status == 'running' && strtolower($backup->status) == 'completed' ? '' : 'disabled';
        $title    = empty($disabled) ? $this->lang->instance->backup->restore : $this->lang->instance->restoreOnlyRunning;
        $btn      = html::commonButton($this->lang->instance->backup->restore, "instance-id='{$instance->id}' title='{$title}' {$disabled} backup-name='{$backup->name}'", "btn-restore btn btn-info");

        echo $btn;
    }

    /**
     * Print button for managing database.
     *
     * @param  object $db
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printDBAction($db, $instance)
    {
        $disabled = $db->ready ? '' : 'disabled';
        $btnHtml  = html::commonButton($this->lang->instance->management, "{$disabled} data-db-name='{$db->name}' data-db-type='{$db->db_type}' data-id='{$instance->id}'", 'db-login btn btn-primary');

        echo $btnHtml;
    }

    /**
     * Print message of action log of instance.
     *
     * @param  object $instance
     * @param  object $log
     * @access public
     * @return void
     */
    public function printLog($instance, $log)
    {
        $action = zget($this->lang->instance->actionList, $log->action, $this->lang->actions);

        $logText = $log->actorName . ' ' . sprintf($action, $instance->name, $log->comment);

        $extra = json_decode($log->extra);
        if(!empty($extra))
        {
            if($log->action == 'editname' && isset($extra->data))
            {
                $oldName  = zget($extra->data, 'oldName', '');
                $newName  = zget($extra->data, 'newName', '');
                $logText .= ', ' . sprintf($this->lang->instance->nameChangeTo, $oldName, $newName);
            }
            elseif($log->action == 'upgrade' && isset($extra->data))
            {
                $oldVersion = zget($extra->data, 'oldVersion', '');
                $newVersion = zget($extra->data, 'newVersion', '');
                $logText   .= ', ' . sprintf($this->lang->instance->versionChangeTo, $oldVersion, $newVersion);
            }
            elseif($log->action == 'tosenior' && isset($extra->data))
            {
                $oldAppName = zget($extra->data, 'oldAppName', '');
                $newAppName = zget($extra->data, 'newAppName', '');
                $logText   .= ', ' . sprintf($this->lang->instance->toSeniorSerial, $oldAppName, $newAppName);
            }
        }

        echo $logText;
    }

    /*
     * Convert CPU digital to readable format.
     *
     * @param  array  $cpuList
     * @access public
     * @return array
     */
    public function getCpuOptions($cpuList)
    {
        $newList = array();
        foreach($cpuList as $cpuValue) $newList[$cpuValue] = $cpuValue . $this->lang->instance->cpuCore;
        return $newList;
    }

    /*
     * Convert memory digital to readable format.
     *
     * @param  array  $memList
     * @access public
     * @return array
     */
    public function getMemOptions($memList)
    {
        $newList = array();
        foreach($memList as $memValue) $newList[$memValue] = helper::formatKB(intval($memValue / 1024));
        return $newList;
    }

    /*
     * Get instance switcher.
     *
     * @param  object  $instance
     * @access public
     * @return string
     */
    public function getSwitcher($instance)
    {
        $instanceLink = helper::createLink('instance', 'view', "id=$instance->id");

        $output  = "<div class='btn-group header-btn'>";
        $output .= html::a($instanceLink, $instance->appName, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of custom installation page of store.
     *
     * @param  object $app
     * @access public
     * @return array
     */
    public function getInstallSwitcher($app)
    {
        $output  = $this->loadModel('store')->getAppViewSwitcher($app);
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('instance', 'install', "id=$app->id"), $this->lang->instance->installApp, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of custom installation page of store.
     *
     * @param  object $app
     * @access public
     * @return array
     */
    public function getCustomInstallSwitcher($app)
    {
        $output  = $this->loadModel('store')->getAppViewSwitcher($app);
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('instance', 'custominstall', "id=$app->id"), $this->lang->instance->customInstall, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get senior app list. The instance can be switched to senior App.
     *
     * @param  object $instance
     * @param  string $channel
     * @access public
     * @return array
     */
    public function seniorAppList($instance, $channel)
    {
        $appList = array();
        foreach(zget($this->config->instance->seniorChartList, $instance->chart, array()) as $chart)
        {
            $cloudApp = $this->loadModel('store')->getAppInfoByChart($chart, $channel, false);
            if($cloudApp) $appList[] = $cloudApp;
        }

        return $appList;
    }
}
