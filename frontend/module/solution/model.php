<?php
/**
 * The model file of solution module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class solutionModel extends model
{

    /**
     * Get solution by id.
     *
     * @param  int         $id
     * @access public
     * @return object|null
     */
    public function getByID($id)
    {
        $solution  = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->eq($id)->fetch();
        if(!$solution) return null;

        $instanceIDList = $this->dao->select('id')->from(TABLE_INSTANCE)->where('solution')->eq($id)->fetchAll('id');

        $solution->instances = array();
        if($instanceIDList) $solution->instances = $this->loadModel('instance')->getByIDList(array_keys($instanceIDList));

        return $solution;
    }

    /**
     * Search
     *
     * @access public
     * @return array
     */
    public function search()
    {
        return $this->dao->select('*')->from(TABLE_SOLUTION)->where('deleted')->eq(0)->orderBy('createdAt desc')->fetchAll();
    }

    /**
     * Create by solution of cloud market.
     *
     * @param  object $cloudSolution
     * @access public
     * @return object
     */
    public function create($cloudSolution, $components)
    {
        $postedCharts = fixer::input('post')->get();

        /* Sort selected apps. */
        $orderedCategories = $components->order;
        $selectedApps = array();
        foreach($orderedCategories as $category)
        {
            $chart = zget($postedCharts, $category);

            $selectedApps[$category] = $this->pickAppFromSchema($components, $category, $chart, $cloudSolution);
        }

        /* Create solution. */
        $solution = new stdclass;
        $solution->name         = $cloudSolution->title;
        $solution->appID        = $cloudSolution->id;
        $solution->appName      = $cloudSolution->name;
        $solution->appVersion   = $cloudSolution->app_version;
        $solution->version      = $cloudSolution->version;
        $solution->chart        = $cloudSolution->chart;
        $solution->cover        = $cloudSolution->background_url;
        $solution->introduction = $cloudSolution->introduction;
        $solution->desc         = $cloudSolution->description;
        $solution->status       = 'waiting';
        $solution->source       = 'cloud';
        $solution->components   = json_encode($selectedApps);
        $solution->createdBy    = $this->app->user->account;
        $solution->createdAt    = date('Y-m-d H:i:s');

        $channel  = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $solution->channel = $channel;

        $this->dao->insert(TABLE_SOLUTION)->data($solution)->exec();
        if(dao::isError()) return null;

        return $this->getByID($this->dao->lastInsertID());
    }

    /**
     * Pick App from schema info by category and chart.
     *
     * @param  object $schema
     * @param  string $category
     * @param  string $chart
     * @param  object $cloudSolution
     * @access public
     * @return object|null
     */
    public function pickAppFromSchema($schema, $category, $chart, $cloudSolution)
    {
        $appGroup = zget($schema->categories, $category, array());

        foreach($appGroup->choices as $appInSchema)
        {
            if($appInSchema->name != $chart) continue;

            $appInfo = zget($cloudSolution->apps, $chart);
            $appInfo->version     = $appInSchema->version;
            $appInfo->app_version = $appInSchema->app_version;
            $appInfo->status      = 'waiting';

            return $appInfo;
        }

        return;
    }

    /**
     * Install solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return bool
     */
    public function install($solutionID)
    {
        ignore_user_abort(true);
        set_time_limit(0);
        session_write_close();

        $solution = $this->getByID($solutionID);
        if(!$solution)
        {
            dao::$errors[] = $this->lang->solution->errors->notFound;
            $this->dao->update(TABLE_SOLUTION)->set('status')->eq('error')->where('id')->eq($solutionID)->exec();
            return false;
        }

        $this->loadModel('instance');
        $this->loadModel('store');
        $channel    = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $components = json_decode($solution->components);
        foreach($components as $componentApp)
        {
            $this->dao->update(TABLE_SOLUTION)->set('status')->eq('installing')->where('id')->eq($solutionID)->exec();
            if($componentApp->status == 'installed') continue;

            $instance = $this->instance->instanceOfSolution($solution, $componentApp->chart);
            /* If not install. */
            if(!$instance)
            {
                $cloudApp = $this->store->getAppInfo($componentApp->id, false, '', '', $channel);
                $instance = $this->instance->apiInstall($cloudApp, '', '', '', $channel);
                if(!$instance)
                {
                    dao::$errors[] = sprintf($this->lang->solution->errors->failToInstallApp, $cloudApp->name);
                    $this->dao->update(TABLE_SOLUTION)->set('status')->eq('error')->where('id')->eq($solutionID)->exec();
                    return false;
                }
                $this->dao->update(TABLE_INSTANCE)->set('solution')->eq($solutionID)->where('id')->eq($instance->id)->exec();

                $componentApp->status = 'installing';
                $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
            }

            /* Query status of the installed instance. */
            $instance = $this->waitInstanceStart($instance);
            if($instance)
            {
                $componentApp->status = 'installed';
                $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
            }
            else
            {
                dao::$errors[] = $this->lang->solution->errors->timeout;
                $this->dao->update(TABLE_SOLUTION)->set('status')->eq('error')->where('id')->eq($solutionID)->exec();
                return false;
            }
        }

        $this->dao->update(TABLE_SOLUTION)->set('status')->eq('finish')->where('id')->eq($solutionID)->exec();
        return true;
    }

    /**
     * Wait instance started.
     *
     * @param  object      $instance
     * @access private
     * @return object|bool
     */
    private function waitInstanceStart($instance)
    {
        /* Query status of the installed instance. */
        $times = 0;
        for($times = 0; $times < 50; $times++)
        {
            sleep(6);
            $instance = $this->instance->freshStatus($instance);
            //$this->app->saveLog(date('Y-m-d H:i:s').' installing ' . $instance->name . ':' .$instance->status);
            if($instance->status == 'running') return $instance;
        }

        return false;
    }

    /**
     * Convert schema choices to select options.
     *
     * @param  object $schemaChoices
     * @param  object $cloudSolution
     * @access public
     * @return array
     */
    public function createSelectOptions($schemaChoices, $cloudSolution)
    {
        $options = array();
        foreach($schemaChoices as $cloudApp)
        {
            $appInfo = zget($cloudSolution->apps, $cloudApp->name);
            $options[$cloudApp->name] = zget($appInfo, 'alias', $cloudApp->name);
        }

        return $options;
    }
}

