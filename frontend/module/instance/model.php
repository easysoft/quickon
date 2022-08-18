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
        $instance = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->eq($id)
            ->andWhere('deleted')->eq(0)
            ->andWhere('createdBy')->eq($this->app->user->account)
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
        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)->where('id')->in($idList)->andWhere('deleted')->eq(0)->fetchAll('id');
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
    public function getByAccount($account = '', $pager = null, $pinned = '', $searchParam = '')
    {
        $instances = $this->dao->select('instance.*')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            ->beginIF($account)->andWhere('space.owner')->eq($account)->fi()
            ->beginIF($pinned)->andWhere('instance.pinned')->eq((int)$pinned)->fi()
            ->beginIF($searchParam)->andWhere('instance.name')->like("%{$searchParam}%")->fi()
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
     * Create instance status.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function createInstance($instance)
    {
        $this->dao->insert(TABLE_INSTANCE)->data($instance)->autoCheck()->exec();

        return $this->getByID($this->dao->lastInsertID());
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
        $busy = in_array($instance->status, array('creating', 'initializing', 'starting', 'stopping', 'suspending', 'destroying'));
        switch($action)
        {
            case 'start':
                return !($busy || in_array($instance->status, array('running', 'abnormal', 'destroyed')));
            case 'stop':
                return !($busy || in_array($instance->status, array('stopped', 'installationFail')));
            case 'uninstall':
                return !$busy;
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
     * @param  int    $thirdDomain
     * @access public
     * @return bool   true: exists, false: not exist.
     */
    public function domainExists($thirdDomain)
    {
        $domain = $this->fullDomain($thirdDomain);
        return boolval($this->dao->select('id')->from(TABLE_INSTANCE)->where('domain')->eq($domain)->andWhere('deleted')->eq(0)->fetch());
    }

    /**
     * Mount installation settings by custom data.
     *
     * @param  object  $customData
     * @param  array   $dbList
     * @param  object  $app
     * @param  object  $instance
     * @access private
     * @return object
     */
    private function installationSettingsMap($customData, $dbList, $app, $instance)
    {
        $settingsMap = new stdclass;
        if($customData->customDomain)
        {
            $settingsMap->ingress = new stdclass;
            $settingsMap->ingress->enabled = true;
            $settingsMap->ingress->host    = $this->fullDomain($customData->customDomain);
        }

        if(empty($customData->dbType) || $customData->dbType == 'unsharedDB' || empty($customData->dbService)) return $settingsMap;

        $selectedDB = zget($dbList, $customData->dbService, '');

        $dbSettings = new stdclass;
        $dbSettings->service   = $customData->dbService;
        $dbSettings->namespace = $selectedDB->namespace;
        $dbSettings->host      = $selectedDB->host;
        $dbSettings->port      = $selectedDB->port;
        $dbSettings->name      = $app->dependencies->mysql->database . '_' . $instance->id;
        $dbSettings->user      = $app->dependencies->mysql->user . '_' . $instance->id;

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
     * Install app.
     *
     * @param  object $app
     * @param  array  $dbList
     * @param  object $customData
     * @param  int    $spaceID
     * @access public
     * @return false|object Failure: return false, Success: return instance
     */
    public function install($app, $dbList, $customData, $spaceID = null)
    {
        $this->loadModel('store');
        $this->app->loadLang('store');

        $this->loadModel('space');
        if($spaceID)
        {
            $space = $this->space->getByID($spaceID);
        }
        else
        {
            $space = $this->space->defaultSpace($this->app->user->account);
        }

        $k8name = "{$app->chart}-{$this->app->user->account}-" . date('YmdHis'); //name rule: chartName-userAccount-YmdHis;

        $instanceData = new stdclass;
        $instanceData->appId        = $app->id;
        $instanceData->appName      = $app->alias;
        $instanceData->name         = !empty($customData->customName)   ? $customData->customName : $app->alias;
        $instanceData->domain       = !empty($customData->customDomain) ? $this->fullDomain($customData->customDomain) : '';
        $instanceData->logo         = $app->logo;
        $instanceData->desc         = $app->desc;
        $instanceData->introduction = isset($app->introduction) ? $app->introduction : $app->desc;
        $instanceData->source       = 'cloud';
        $instanceData->channel      = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $instanceData->chart        = $app->chart;
        $instanceData->appVersion   = $customData->app_version ? $customData->app_version : $app->app_version;
        $instanceData->version      = $customData->version;
        $instanceData->space        = $space->id;
        $instanceData->k8name       = $k8name;
        $instanceData->status       = 'creating';
        $instanceData->createdBy    = $this->app->user->account;
        $instanceData->createdAt    = date('Y-m-d H:i:s');

        $instance = $this->createInstance($instanceData);
        if(dao::isError()) return false;

        $apiParams = new stdclass;
        $apiParams->userame      = $this->app->user->account;
        $apiParams->cluser       = '';
        $apiParams->namespace    = $space->k8space;
        $apiParams->name         = $k8name;
        $apiParams->chart        = $app->chart;
        $apiParams->version      = $instance->version;
        $apiParams->channel      = $instance->channel;
        $apiParams->settings_map = $this->installationSettingsMap($customData, $dbList, $app, $instance);

        $result = $this->cne->installApp($apiParams);
        if($result->code != 200)
        {
            $this->dao->delete()->from(TABLE_INSTANCE)->where('id')->eq($instance->id)->exec();
            return false;
        }

        $this->loadModel('action')->create('instance', $instance->id, 'install', '', json_encode(array('result' => $result, 'app' => $app)));

        $status = $result->code == 200 ? 'initializing' : 'installationFail';
        $this->updateByID($instance->id, array('status' => $status, 'dbSettings' => json_encode($apiParams->settings_map)));

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
        $apiPrams = new stdclass;
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

    /*
     * Query and update instances status.
     *
     * @param  array $instances
     * @access public
     * @return array  new status list [{id:xx, status: xx, changed: true/false}]
     */
    public function batchFresh(&$instances)
    {
        $statusList   = array();
        foreach($instances as $instance)
        {
            $instance = $this->freshStatus($instance);

            $status = new stdclass;
            $status->id     = $instance->id;
            $status->status = $instance->status;

            $statusList[] = $status;
        }

        return $statusList;
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
        $instance->runDuration = intval($statusData->age);

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
            $backup->username = zget($users, $backup->creator);
            foreach($backup->restores as &$restore) $restore->username = zget($users, $restore->creator);
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
        $k8AppList = $this->cne->instancelist();
        $k8NameList = array_keys($k8AppList);

        //软删除不存在的数据
        $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('k8name')->notIn($k8NameList)->exec();
        foreach($k8AppList as $k8App)
        {
            $existInstance = $this->dao->select('id')->from(TABLE_INSTANCE)->where('k8name')->eq($k8App->name)->fetch();
            if($existInstance) continue;

            $marketApp = $this->cne->getAppInfo(0, false, $k8App->chart, $k8App->version,  $k8App->channel);
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
     * @param  string    $k8Name
     * @access public
     * @return mixed
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
            ->fetchAll('space');
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
     * @param  object $metrics
     * @param  string $type    'bar' is progress bar, 'pie' is progress pie.
     * @static
     * @access public
     * @return viod
     */
    public static function printCpuUsage($metrics, $type = 'bar')
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->limit}";

        if(strtolower($type) == 'pie')
        {
            return commonModel::printProgressPie($rate, '', $tip);
        }

        return commonModel::printProgressBar($rate, '', $tip);
    }

    /**
     * Print memory usage.
     *
     * @param  object $metrics
     * @param  string $type    'bar' is progress bar, 'pie' is progress pie.
     * @static
     * @access public
     * @return viod
     */
    public static function printMemUsage($metrics, $type = 'bar')
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage / 1024) . ' / ' . helper::formatKB($metrics->limit / 1024);

        if(strtolower($type) == 'pie')
        {
            return commonModel::printProgressPie($rate, '', $tip);
        }

        return commonModel::printProgressBar($rate, '', $tip);
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
            $actionHtml  .= html::a('//'.$instance->domain, '<i class="icon icon-menu-my"></i>', '_blank', "title='{$this->lang->instance->visit}' class='btn btn-lg btn-action btn-link'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
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
             $actionHtml  .= html::a('//'.$instance->domain, $this->lang->instance->visit, '_blank', "title='{$this->lang->instance->visit}' class='btn btn-primary label-lg'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
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
        $btnHtml = html::commonButton($this->lang->instance->management, "{$disabled} data-db-name='{$db->name}' data-id='{$instance->id}'", 'db-login btn btn-primary');

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

        $logText = $log->actorName  . ' ' . sprintf($action, $instance->name);

        $extra = json_decode($log->extra);
        if(!empty($extra))
        {
            if($log->action == 'editname' && isset($extra->data))
            {
                $oldName  = zget($extra->data, 'oldName', '');
                $newName  = zget($extra->data, 'newName', '');
                $logText .= ', ' . sprintf($this->lang->instance->nameChangeTo, $oldName, $newName);
            }
            if($log->action == 'upgrade' && isset($extra->data))
            {
                $oldVersion = zget($extra->data, 'oldVersion', '');
                $newVersion = zget($extra->data, 'newVersion', '');
                $logText .= ', ' . sprintf($this->lang->instance->versionChangeTo, $oldVersion, $newVersion);
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
}
