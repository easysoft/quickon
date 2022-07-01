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
    /**
     * Construct function: set api headers.
     *
     * @param  string $appName
     * @access public
     * @return mixed
     */
    public function __construct($appName = '')
    {
        parent::__construct($appName);

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
     * Get app list from cloud market.
     *
     * @param  string $keyword
     * @param  array  $categories
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    public function searchApps($keyword = '', $categories = array(), $page = 1, $pageSize = 20)
    {
        $apiUrl  = '/api/market/applist?channel='. $this->config->cloud->api->channel;
        $apiUrl .= "&q=$keyword";
        $apiUrl .= "&page=$page";
        $apiUrl .= "&page_size=$pageSize";
        foreach($categories as $category) $apiUrl .= "&category=$category"; // Same name reason is CNE api is required.

        $result = $this->apiGet($apiUrl, array(), $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if($result->code == 200) return $result->data;

        $pagedApps = new stdclass;
        $pagedApps->apps  = array();
        $pagedApps->total = 0;
        return $pagedApps;
    }

    /**
     * Get default username and password of app.
     *
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getDefaultAccount($id)
    {
        $apiUrl = '/api/market/app/account?channel='. $this->config->cloud->api->channel;
        $result = $this->apiGet($apiUrl, array('id' => $id), $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        $account = $result->data;
        if(isset($account->username) && $account->username && isset($account->password) && $account->password) return $account;

        return null;
    }

    /**
     * Get app info from cloud market.
     *
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getAppInfo($id)
    {
        $apiUrl = '/api/market/appinfo';
        $result = $this->apiGet($apiUrl, array('id' => $id), $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        return $result->data;
    }

    /**
     * Get upgradable versions of app from cloud market.
     *
     * @param  string $currentVersion
     * @param  int    $appID          appID is required if no appName.
     * @param  string $appName        appName is required if no appID.
     * @param  string $channel
     * @access public
     * @return mixed
     */
    public function getUpgradableVersions($currentVersion, $appID = 0, $appName = '', $channel = '')
    {
        $channel = $channel ? $channel : $this->config->cloud->api->channel;
        $apiUrl  = '/api/market/app/version/upgradable';

        $conditions = array('version' => $currentVersion, 'channel' => $channel);
        if($appID)
        {
            $conditions['id'] = $appID;
        }
        else
        {
            $conditions['name'] = $appName;
        }

        $result  = $this->apiGet($apiUrl, $conditions, $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if(!isset($result->code) || $result->code != 200) return array();

        return $result->data;
    }

    /**
     * Get the latest version of QuCheng platform.
     *
     * @access public
     * @return object
     */
    public function platformLatestVersion()
    {
        $versionList = $this->getUpgradableVersions($this->config->platformVersion, 0, 'qucheng', $this->config->cloud->api->channel);

        $latestVersion = $this->pickHighestVersion($versionList, $this->config->platformVersion);
        if(empty($latestVersion->app_version)) $latestVersion->app_version = getenv('APP_VERSION');

        return $latestVersion;
    }

    /**
     * Get the latest versions of app from cloud market.
     *
     * @param  int    $appID
     * @param  string $currentVersion
     * @access public
     * @return string
     */
    public function appLatestVersion($appID, $currentVersion)
    {
        $versionList = $this->getUpgradableVersions($currentVersion, $appID);
        $versionData = $this->pickHighestVersion($versionList, $currentVersion);
        return $versionData->version;
    }


    /**
     * Pick highest version from version list and compared version.
     *
     * @param  int    $versionList
     * @param  string $comparedVersion
     * @access private
     * @return mixed
     */
    private function pickHighestVersion($versionList, $comparedVersion = '0.0.0')
    {
        $highestVersion = new stdclass;
        $highestVersion->version = $comparedVersion;
        foreach($versionList as $version)
        {
            if(version_compare(str_replace('-', '.', $version->version), str_replace('-', '.', $highestVersion->version), '>')) $highestVersion = $version;
        }

        return $highestVersion;
    }

    /**
     * Get app setting from cloud market.
     *
     * @param  int $id
     * @access public
     * @return array
     */
    public function getAppSettings($id)
    {
        $apiUrl = '/api/market/appsettings';
        $result = $this->apiGet($apiUrl, array('id' => $id), $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if($result->code != 200) return array();

        /* Convert "." to "_" */
        $components = $result->data->components;
        foreach($result->data->components as &$component)
        {
            foreach($component->settings as $setting) $setting->field = str_replace('.', '_', $setting->field);
        }

        return $components;
    }

    /**
     * Get category list from cloud market.
     *
     * @access public
     * @return object
     */
    public function getCategories()
    {
        $apiUrl = '/api/market/categories';
        $result = $this->apiGet($apiUrl, array(), $this->config->cloud->api->headers, $this->config->cloud->api->host);
        if($result->code == 200) return $result->data;

        $categories= new stdclass;
        $categories->categories = array();
        $categories->total      = 0;
        return $categories;
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
        $setting['channel']   = $this->config->CNE->api->channel;
        $setting['chart']     = $instance->chart;
        $setting['version']   = $toVersion;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $setting, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
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

        $statistics->metrics->memory->rate = $statistics->metrics->memory->capacity > 0 ? round( $statistics->metrics->memory->usage / $statistics->metrics->memory->capacity * 100, 2) : 0;

        $statistics->metrics->cpu->rate        = $statistics->metrics->cpu->capacity > 0 ? round( $statistics->metrics->cpu->usage / $statistics->metrics->cpu->capacity * 100, 2) : 0;
        $statistics->metrics->cpu->usage       = round($statistics->metrics->cpu->usage, 4);
        $statistics->metrics->cpu->capacity    = round($statistics->metrics->cpu->capacity, 4);
        $statistics->metrics->cpu->allocatable = round($statistics->metrics->cpu->allocatable, 4);

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

            $instancesMetrics[$k8sMetric->name]->cpu->limit = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->limit) ? round($k8sMetric->metrics->cpu->limit, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->usage = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->usage) ? round($k8sMetric->metrics->cpu->usage, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->rate  = $k8sMetric->metrics->cpu->limit > 0 ? round($k8sMetric->metrics->cpu->usage / $k8sMetric->metrics->cpu->limit * 100, 2) : 0;

            $instancesMetrics[$k8sMetric->name]->memory->limit = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->limit) ? $k8sMetric->metrics->memory->limit : 0;
            $instancesMetrics[$k8sMetric->name]->memory->usage = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->usage) ? $k8sMetric->metrics->memory->usage : 0;
            $instancesMetrics[$k8sMetric->name]->memory->rate  = $k8sMetric->metrics->memory->limit > 0 ? round($k8sMetric->metrics->memory->usage / $k8sMetric->metrics->memory->limit * 100, 2) : 0;
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
        $color = 'red';
        if($rate == 0)     $color = 'gray';
        elseif($rate < 60) $color = 'green';
        elseif($rate < 80) $color = 'orange';

        $tip = "{$rate}% = {$metrics->usage} / {$metrics->capacity}";
        commonModel::printProgress($rate, $color, $tip);
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
        $color = 'red';
        if($rate == 0)     $color = 'gray';
        elseif($rate < 60) $color = 'green';
        elseif($rate < 80) $color = 'orange';

        $tip = "{$rate}% = " . helper::formatKB($metrics->usage / 1024) . ' / ' . helper::formatKB($metrics->capacity / 1024);
        commonModel::printProgress($rate, $color, $tip);
    }

    /**
     * Start an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function startApp($instance)
    {
        $instance->channel = $this->config->CNE->api->channel;
        $apiUrl = "/api/cne/app/start";
        return $this->apiPost($apiUrl, $instance, $this->config->CNE->api->headers);
    }

    /**
     * Stop an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function stopApp($instance)
    {
        $instance->channel = $this->config->CNE->api->channel;
        $apiUrl = "/api/cne/app/stop";
        return $this->apiPost($apiUrl, $instance, $this->config->CNE->api->headers);
    }

    /**
     * Install app.
     *
     * @param  object $appData
     * @access public
     * @return object
     */
    public function installApp($appData)
    {
        $appData->settings = $this->trasformSettings($appData->settings);

        $appData->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/install";
        return $this->apiPost($apiUrl, $appData, $this->config->CNE->api->headers);
    }

    /**
     * uninstall an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function uninstallApp($instance)
    {
        $instance->channel = $this->config->CNE->api->channel;
        $apiUrl = "/api/cne/app/uninstall";
        return $this->apiPost($apiUrl, $instance, $this->config->CNE->api->headers);
    }

    /**
     * Config app instance.
     *
     * @param  int    $instance
     * @param  int    $settings
     * @access public
     * @return true
     */
    public function configApp($instance, $settings)
    {
        $instance->settings = $this->transformedSettings($settings);
        $instance->channel  = $this->config->CNE->api->channel;
        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $instance, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * Trasform setting format.
     *
     * @param  array  $settings
     * @access private
     * @return aray
     */
    private function trasformSettings($settings)
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
     * Query status of an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object|null
     */
    public function queryStatus($instance)
    {
        $instance->channel = $this->config->CNE->api->channel;
        $apiUrl = "/api/cne/app/status";
        $result = $this->apiGet($apiUrl, $instance, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result->data;

        return null;
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
        $requestUri .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data);
        $result      = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'GET'), $header, 'json', 20));
        if($result && $result->code == 200) return $result;

        return $this->getError();
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

        return $this->getError();
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

        return $this->getError();
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

        return $this->getError();
    }

    /**
     * Return error object of api server.
     *
     * @param int      $code error code
     * @param string   $message error message
     * @access protected
     * @return object
     */
    protected function getError($code = 600, $message = '')
    {
        $error = new stdclass;
        $error->code    = $code;
        $error->message = $message ? $message : $this->lang->CNE->serverError;
        return $error;
    }
}
