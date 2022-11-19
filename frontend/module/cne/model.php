<?php
/**
 * The model file of CNE(Cloud Native Engine) module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   CNE
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class cneModel extends model
{
    protected $error;

    /**
     * Construct function: set api headers.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($appName = '')
    {
        parent::__construct($appName);

        $this->error = new stdclass;

        global $config, $app;
        $config->CNE->api->headers[]   = "{$config->CNE->api->auth}: {$config->CNE->api->token}";
        $config->cloud->api->headers[] = "{$config->cloud->api->auth}: {$config->cloud->api->token}";

        if($config->cloud->api->switchChannel && $app->session->cloudChannel)
        {
            $config->cloud->api->channel = $app->session->cloudChannel;
            $config->CNE->api->channel   = $app->session->cloudChannel;
        }
    }

    /**
     * Get all instance of app in cluster.
     *
     * @access public
     * @return array
     */
    public function instanceList()
    {
        $apiUrl = "/api/cne/system/app-full-list";
        $result = $this->apiGet($apiUrl, array(), $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $instanceList = $result->data;
        return array_combine(array_column($instanceList, 'name'), $instanceList);
    }

    /**
     * Upgrade platform.
     *
     * @param  string $toVersion
     * @access public
     * @return bool
     */
    public function upgradePlatform($toVersion)
    {
        $apiUrl = "/api/cne/system/update";
        $result = $this->apiPost($apiUrl, array('version' => $toVersion, 'channel' => $this->config->CNE->api->channel), $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Upgrade or degrade platform version.
     *
     * @param  string $version
     * @access public
     * @return bool
     */
    public function setPlatformVersion($version)
    {
        $instance = new stdclass;
        $instance->k8name = 'qucheng';
        $instance->chart  = 'qucheng';

        $instance->spaceData = new stdclass;
        $instance->spaceData->k8space = 'cne-system';

        return $this->upgradeToVersion($instance, $version);
    }

    /**
     * Upgrade app instance to version.
     *
     * @param  object $instance
     * @param  string $toVersion
     * @access public
     * @return bool
     */
    public function upgradeToVersion($instance, $toVersion = '')
    {
        $setting = array();
        $setting['cluster']   = '';
        $setting['namespace'] = $instance->spaceData->k8space;
        $setting['name']      = $instance->k8name;
        $setting['channel']   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;
        $setting['chart']     = $instance->chart;
        $setting['version']   = $toVersion;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $setting, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Update instance config. For example: cpu, memory size, LDAP settings...
     *
     * @param  object $instance
     * @param  object $settings
     * @access public
     * @return bool
     */
    public function updateConfig($instance, $settings)
    {
        $apiParams = array();
        $apiParams['cluster']   = '';
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['name']      = $instance->k8name;
        $apiParams['channel']   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;
        $apiParams['chart']     = $instance->chart;
        $apiParams['version']   = $instance->version;

        if(isset($settings->settings_map))      $apiParams['settings_map']      = $settings->settings_map;
        if(isset($settings->settings_snippets)) $apiParams['settings_snippets'] = $settings->settings_snippets;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Get default username and password of app.
     *
     * @param  object $instance
     * @param  string $cluster
     * @access public
     * @return object|null
     */
    public function getDefaultAccount($instance, $cluster = '', $component = '')
    {
        $apiUrl = '/api/cne/app/account?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = $cluster;
        if($component) $apiParams['component'] = $component;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers, $this->config->CNE->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        $account = $result->data;
        if(isset($account->username) && $account->username && isset($account->password) && $account->password) return $account;

        return null;
    }

    /**
     * Get domain.
     *
     * @param  object $instance
     * @param  string $cluster
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDomain($instance, $cluster = '', $component = '')
    {
        $apiUrl = '/api/cne/app/domain?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = $cluster;
        if($component) $apiParams['component'] = $component;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers, $this->config->CNE->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        return $result->data;
    }

    /**
     * Get cluster metrics of CNE platform.
     *
     * @param  string $cluster
     * @access public
     * @return object
     */
    public function cneMetrics($cluster = '')
    {
        $metrics = new stdclass;
        $metrics->cpu = new stdclass;
        $metrics->cpu->usage       = 0;
        $metrics->cpu->capacity    = 0;
        $metrics->cpu->allocatable = 0;
        $metrics->cpu->rate        = 0;

        $metrics->memory = new stdclass;
        $metrics->memory->usage       = 0;
        $metrics->memory->capacity    = 0;
        $metrics->memory->allocatable = 0;
        $metrics->memory->rate        = 0;

        $statistics = new stdclass;
        $statistics->status     = 'unknown';
        $statistics->node_count = 0;
        $statistics->metrics    = $metrics;

        $apiUrl = "/api/cne/statistics/cluster";
        $result = $this->apiGet($apiUrl, array('cluster' => $cluster), $this->config->CNE->api->headers);
        if($result->code != 200) return $statistics;

        $statistics = $result->data;

        $statistics->metrics->cpu->usage       = max(round($statistics->metrics->cpu->usage, 4), 0);
        $statistics->metrics->cpu->capacity    = max(round($statistics->metrics->cpu->capacity, 4), $statistics->metrics->cpu->usage);
        $statistics->metrics->cpu->allocatable = round($statistics->metrics->cpu->allocatable, 4);
        $statistics->metrics->cpu->rate        = $statistics->metrics->cpu->capacity > 0 ? round( $statistics->metrics->cpu->usage / $statistics->metrics->cpu->capacity * 100, 2) : 0;
        $statistics->metrics->cpu->rate        = min($statistics->metrics->cpu->rate, 100);

        $statistics->metrics->memory->usage    = max(round($statistics->metrics->memory->usage, 4), 0);
        $statistics->metrics->memory->capacity = max($statistics->metrics->memory->capacity, $statistics->metrics->memory->usage);
        $statistics->metrics->memory->rate     = $statistics->metrics->memory->capacity > 0 ? round($statistics->metrics->memory->usage / $statistics->metrics->memory->capacity * 100, 2) : 0;
        $statistics->metrics->memory->rate     = min($statistics->metrics->memory->rate, 100);
        return $statistics;
    }

    /**
     * Get instance metrics.
     *
     * @param  array  $instances
     * @access public
     * @return object
     */
    public function instancesMetrics($instances)
    {
        $instancesMetrics = array();

        $apiData = array('cluster' => '', 'apps' => array());
        foreach($instances as $instance)
        {
            $appData = new stdclass;
            $appData->name      = $instance->k8name;
            $appData->namespace = $instance->spaceData->k8space;
            $apiData['apps'][]  = $appData;

            $instanceMetric = new stdclass;
            $instanceMetric->id        = $instance->id;
            $instanceMetric->name      = $instance->k8name;
            $instanceMetric->namespace = $instance->spaceData->k8space;

            $instanceMetric->cpu = new stdclass;
            $instanceMetric->cpu->limit = 0;
            $instanceMetric->cpu->usage = 0;
            $instanceMetric->cpu->rate  = 0;

            $instanceMetric->memory = new stdclass;
            $instanceMetric->memory->limit = 0;
            $instanceMetric->memory->usage = 0;
            $instanceMetric->memory->rate  = 0;

            $instancesMetrics[$instance->k8name] = $instanceMetric;
        }

        $apiUrl = "/api/cne/statistics/app";
        $result = $this->apiPost($apiUrl, $apiData, $this->config->CNE->api->headers);
        if(!isset($result->code) || $result->code != 200)return array_combine(array_column($instancesMetrics, 'id'), $instancesMetrics);

        foreach($result->data as $k8sMetric)
        {
            if(!isset($k8sMetric->metrics)) continue;

            $instancesMetrics[$k8sMetric->name]->cpu->usage = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->usage) ? round($k8sMetric->metrics->cpu->usage, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->usage = max($instancesMetrics[$k8sMetric->name]->cpu->usage, 0);
            $instancesMetrics[$k8sMetric->name]->cpu->limit = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->limit) ? round($k8sMetric->metrics->cpu->limit, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->limit = max($instancesMetrics[$k8sMetric->name]->cpu->limit, $instancesMetrics[$k8sMetric->name]->cpu->usage);
            $instancesMetrics[$k8sMetric->name]->cpu->rate  = $instancesMetrics[$k8sMetric->name]->cpu->limit > 0 ? round($instancesMetrics[$k8sMetric->name]->cpu->usage / $instancesMetrics[$k8sMetric->name]->cpu->limit * 100, 2) : 0;

            $instancesMetrics[$k8sMetric->name]->memory->usage = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->usage) ? $k8sMetric->metrics->memory->usage : 0;
            $instancesMetrics[$k8sMetric->name]->memory->usage = max($instancesMetrics[$k8sMetric->name]->memory->usage, 0);
            $instancesMetrics[$k8sMetric->name]->memory->limit = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->limit) ? $k8sMetric->metrics->memory->limit : 0;
            $instancesMetrics[$k8sMetric->name]->memory->limit = max( $instancesMetrics[$k8sMetric->name]->memory->limit, $instancesMetrics[$k8sMetric->name]->memory->usage);
            $instancesMetrics[$k8sMetric->name]->memory->rate  = $instancesMetrics[$k8sMetric->name]->memory->limit > 0 ? round($instancesMetrics[$k8sMetric->name]->memory->usage / $instancesMetrics[$k8sMetric->name]->memory->limit * 100, 2) : 0;
        }

        return array_combine(array_column($instancesMetrics, 'id'), $instancesMetrics);
    }

    /**
     * Print CPU usage.
     *
     * @param  object    $metrics
     * @static
     * @access public
     * @return viod
     */
    public static function printCpuUsage($metrics)
    {
        $rate  = $metrics->rate;

        $tip = "{$rate}% = {$metrics->usage} / {$metrics->capacity}";
        commonModel::printProgressBar($rate, '', $tip, 'percent');
    }

    /**
     * Print memory usage.
     *
     * @param  object    $metrics
     * @static
     * @access public
     * @return viod
     */
    public static function printMemUsage($metrics)
    {
        $rate  = $metrics->rate;

        $tip = "{$rate}% = " . helper::formatKB($metrics->usage / 1024) . ' / ' . helper::formatKB($metrics->capacity / 1024);
        commonModel::printProgressBar($rate, '', $tip, 'tip');
    }

    /**
     * Backup service in k8s cluster.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function backup($instance, $account)
    {
        $apiParams = new stdclass;
        $apiParams->username  = $account;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Stauts of backup progress.
     *
     * @param  object $instance
     * @param  object $backup
     * @access public
     * @return mixed
     */
    public function backupStatus($instance, $backup)
    {
        $apiParams = new stdclass;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backup->backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Backup list.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function backupList($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backups";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Backup service in k8s cluster.
     *
     * @param  object $instance
     * @param  object $backupName
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function restore($instance, $backupName, $account)
    {
        $apiParams = new stdclass;
        $apiParams->username    = $account;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/restore";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Stauts of restore progress.
     *
     * @param  object $instance
     * @param  object $restore
     * @access public
     * @return object
     */
    public function restoreStatus($instance, $restore)
    {
        $apiParams = new stdclass;
        $apiParams->cluster      = '';
        $apiParams->namespace    = $instance->spaceData->k8space;
        $apiParams->name         = $instance->k8name;
        $apiParams->restore_name = $restore->restoreName;
        $apiParams->channel      = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/restore/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Start an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function startApp($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/start";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Stop an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function stopApp($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/stop";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Create snippet.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function addSnippet($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/add";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Update snippet.
     *
     * @param  object $apiParams
     * @access public
     * @return mixed
     */
    public function updateSnippet($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/update";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Remove snippet
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function removeSnippet($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/remove";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Install app.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function installApp($apiParams)
    {
        if(!empty($apiParams->settings)) $apiParams->settings = $this->trasformSettings($apiParams->settings);

        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/install";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * uninstall an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function uninstallApp($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/uninstall";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * Validate SMTP settings.
     *
     * @param  object $apiParams
     * @access public
     * @return bool
     */
    public function validateSMTP($apiParams)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/system/smtp/validator";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Config app instance.
     *
     * @param  object $apiParams
     * @param  object $settings
     * @access public
     * @return true
     */
    public function configApp($apiParams, $settings)
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiParams->settings = $this->transformSettings($settings);
        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Transform setting format.
     *
     * @param  array  $settings
     * @access private
     * @return aray
     */
    private function transformSettings($settings)
    {
        $transformedSettings = array();
        foreach($settings as $key => $value)
        {
            if(strpos($key, 'replicas') !== false && intval($value) < 1) $value = 1; // Replicas must be greater 0.
            $transformedSettings[] = array('key' => str_replace('_', '.', $key), 'value' => $value);
        }
        return $transformedSettings;
    }

    /**
     * Get app config and resource.
     *
     * @param  object      $instance
     * @access public
     * @return bool|object
     */
    public function getAppConfig($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;

        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/settings/common";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return false;

        $result->data->min = $result->data->oversold;
        $result->data->max = $result->data->resources;
        return $result->data;
    }

    /**
     * Get custom items of app.
     *
     * @param  object      $instance
     * @access public
     * @return bool|object
     */
    public function getCustomItems($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;

        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/settings/custom";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return false;

        return $result->data;
    }

    /**
     * Query status of an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object|null
     */
    public function queryStatus($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/status";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result;

        return $result;
    }

    /**
     * Query status of instance list.
     *
     * @param  object $instanceList
     * @access public
     * @return object|null
     */
    public function batchQueryStatus($instanceList)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->apps = array();

        foreach($instanceList as $instance)
        {
            $app = new stdclass;
            $app->name      = $instance->k8name;
            $app->chart     = $instance->chart;
            $app->namespace = $instance->spaceData->k8space;
            $app->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

            $apiParams->apps[] = $app;
        }

        $apiUrl = "/api/cne/app/status/multi";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200) return array();

        $statusList = array();
        foreach($result->data as $status) $statusList[$status->name] = $status;

        return $statusList;
    }

    /**
     * Get all database list of app.
     *
     * @param  object  $instance
     * @access public
     * @return mixed
     */
    public function appDBList($instance)
    {
        $apiUrl    = "/api/cne/app/dbs";
        $apiParams =  array();
        $apiParams['cluster']   = 'default';
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['channel']   = $this->config->CNE->api->channel;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(array_column($dbList, 'name'), $dbList);
    }

    /**
     * Get detail of app database.
     *
     * @param  object $instance
     * @param  string $dbName
     * @access public
     * @return null|object
     */
    public function appDBDetail($instance, $dbName)
    {
        $apiParams =  array();
        $apiParams['cluster']   = 'default';
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['db']        = $dbName;
        $apiParams['channel']   = $this->config->CNE->api->channel;

        $apiUrl    = "/api/cne/app/dbs/detail";

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return;

        return $result->data;
    }

    /**
     * Get database detail.
     *
     * @param  string $dbService
     * @param  string $namespace
     * @access public
     * @return null|object
     */
    public function dbDetail($dbService, $namespace)
    {
        $apiUrl    = "/api/cne/component/dbservice/detail";
        $apiParams =  array('name' => $dbService, 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return;

        return $result->data;
    }

    /**
     * Get all database list.
     *
     * @param  string  $namespace
     * @param  boolean $global true: global database
     * @access public
     * @return mixed
     */
    public function allDBList($global = true, $namespace = 'default')
    {
        $apiUrl    = "/api/cne/component/dbservice";
        $apiParams =  array( 'global' => ($global ? 'true' : 'false'), 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(array_column($dbList, 'name'), $dbList);
    }

    /**
     * Get shared database list.
     *
     * @param  string $dbType    database type.
     * @param  string $namespace
     * @access public
     * @return array
     */
    public function sharedDBList($dbType = 'mysql', $namespace = 'default')
    {
        $apiUrl    = "/api/cne/component/gdb";
        $apiParams =  array('kind' => $dbType, 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(array_column($dbList, 'name'), $dbList);
    }

    /**
     * Validate database name and user.
     *
     * @param  string $dbService
     * @param  string $dbUser
     * @param  string $dbName
     * @param  string $namespace
     * @access public
     * @return object
     */
    public function validateDB($dbService, $dbUser, $dbName, $namespace)
    {
        $apiParams = array();
        $apiParams['name']      = $dbService;
        $apiParams['user']      = $dbUser;
        $apiParams['database']  = $dbName;
        $apiParams['namespace'] = $namespace;

        $apiUrl = "/api/cne/component/gdb/validation";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result->data->validation;

        $validation = new stdclass;
        $validation->user     = true;
        $validation->database = true;

        return $validation;
    }

    /**
     * Get method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header example: array("key1:value1", "key2:value2")
     * @param  string       $host
     * @access public
     * @return object
     */
    public function apiGet($url, $data, $header = array(), $host = '')
    {
        $requestUri  = ($host ? $host : $this->config->CNE->api->host) . $url;
        $requestUri .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data, null, '&', PHP_QUERY_RFC3986);
        $result      = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'GET'), $header, 'json', 20));
        if($result && $result->code == 200) return $result;
        if($result && $result->code != 200) return $this->translateError($result);;

        return $this->cneServerError();
    }

    /**
     * Post method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header example: array("key1:value1", "key2:value2")
     * @param  string       $host
     * @access public
     * @return object
     */
    public function apiPost($url, $data, $header = array(), $host = '')
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header, 'json', 20));
        if($result && $result->code == 200) return $result;
        if($result && $result->code != 200) return $this->translateError($result);;

        return $this->cneServerError();
    }

    /**
     * Put method of API.
     *
     * @param  string        $url
     * @param  array|object  $data
     * @param  array         $header example: array("key1:value1", "key2:value2")
     * @param  string        $host
     * @access public
     * @return object
     */
    public function apiPut($url, $data, $header = array(), $host = '')
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $header, 'json', 20));
        if($result && $result->code == 200) return $result;
        if($result && $result->code != 200) return $this->translateError($result);;

        return $this->cneServerError();
    }

    /**
     * Delete method of API.
     *
     * @param  string        $url
     * @param  array|object  $data
     * @param  array         $header example: array("key1:value1", "key2:value2")
     * @param  string        $host
     * @access public
     * @return object
     */
    public function apiDelete($url, $data, $header = array(), $host = '')
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $header, 'json', 20));
        if($result && $result->code == 200) return $result;
        if($result && $result->code != 200) return $this->translateError($result);;

        return $this->cneServerError();
    }

    /**
     * Return error object of api server.
     *
     * @access protected
     * @return object
     */
    protected function cneServerError()
    {
        $error = new stdclass;
        $error->code    = 600;
        $error->message = $this->lang->CNE->serverError;
        return $error;
    }

    /**
     * Get error
     *
     * @access public
     * @return object
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Translate error message for multi language.
     *
     * @param  object    $apiResult
     * @access protected
     * @return void
     */
    protected function translateError(&$apiResult)
    {
        $this->error->code    = $apiResult->code;
        $this->error->message = zget($this->lang->CNE->errorList, $apiResult->code, $this->lang->CNE->serverError); // Translate CNE api error message to multi language.

        $apiResult->message = $this->error->message;

        return $this->error;
    }
}
