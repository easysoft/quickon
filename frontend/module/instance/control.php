<?php
/**
 * The control file of instance module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class instance extends control
{
    /**
     * Construct function.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('action');
        $this->loadModel('cne');
    }

    /**
     * Show instance view.
     *
     * @param  int $id
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $page
     * @access public
     * @return void
     */
    public function view($id, $recTotal = 0, $recPerPage = 20, $pageID = 1 )
    {
        $instance = $this->instance->getByID($id);
        if(empty($instance))return print(js::alert($this->lang->instance->instanceNotExists) . js::locate($this->createLink('space', 'browse')));

        $instance = $this->instance->freshStatus($instance);

        $this->lang->switcherMenu = $this->instance->getSwitcher($instance);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->position[] = $instance->appName;

        $this->view->title          = $instance->appName;
        $this->view->instance       = $instance;
        $this->view->logs           = $this->action->getList('instance', $id, 'date desc', $pager);
        $this->view->defaultAccount = $this->cne->getDefaultAccount($instance->appID);
        $this->view->pager          = $pager;

        $this->display();
    }

    /**
     * Edit instance app name.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function editName($id)
    {
        $instance = $this->instance->getByID($id);

        if(!empty($_POST))
        {
            $newInstance = fixer::input('post')->trim('name')->get();
            $this->instance->updateByID($id, $newInstance);
            if(dao::isError())
            {
                $this->action->create('instance', $instance->id, 'editname', '', json_encode(array('result' => array('code' => 600), 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->action->create('instance', $instance->id, 'editname', '', json_encode(array('result' => array('code' => 200), 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
            return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
        }

        $this->view->title    = $instance->name;
        $this->view->instance = $instance;

        $this->view->position[] = $this->lang->instance->editName;

        $this->display();
    }

    /**
     * Install app by custom settings.
     *
     * @param int $id
     * @access public
     * @return void
     */
    public function customInstall($id)
    {
        // Disable custom installation in version 1.0.
        $storeUrl = $this->createLink('store', 'appview', "id=$id");
        return js::execute("window.parent.location.href='{$storeUrl}';");

        $cloudApp = $this->cne->getAppInfo($id);
        if(empty($cloudApp)) return print(js::locate('back', 'parent'));

        $components = $this->cne->getAppSettings($id);

        if(!empty($_POST))
        {
            $postSettings = fixer::input('post')->get();
            foreach($postSettings as $key => $value) if(strpos($key, 'replicas') !== false && $value < 1) $this->send(array('result' => 'fail', 'message' => $this->lang->instance->caplicasTooSmall));

            if(!$this->instance->install($cloudApp, $postSettings)) return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'installFail')));

            return $this->send(array('result'=>'success', 'message' => '', 'locate' => $this->createLink('space', 'browse')));
        }

        $this->lang->switcherMenu = $this->instance->getCustomInstallSwitcher($cloudApp);

        $this->view->position[] = $this->lang->instance->customInstall;

        $this->view->title      = $this->lang->instance->customInstall;
        $this->view->activeTab  = isset($components[0]) ? $components[0]->name : '';
        $this->view->components = $components;
        $this->view->appID      = $id;

        $this->display();
    }

    /**
     * Install app.
     *
     * @param  int $appID
     * @access public
     * @return void
     */
    public function ajaxInstall($appID)
    {
        $appInfo= $this->cne->getAppInfo($appID);

        $response = array();
        $response['result']  = 'fail';
        $response['message'] = zget($this->lang->instance->notices, 'installFail');

        if($this->instance->install($appInfo))
        {
            $response['result']  = 'success';
            $response['message'] = zget($this->lang->instance->notices, 'installSuccess');
            $response['locate']  = helper::createLink('space', 'browse');
        }

        return $this->send($response);
    }

    /**
     * Uninstall app instance.
     *
     * @param  int $instanceID
     * @access public
     * @return void
     */
    public function ajaxUninstall($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['success']));

        $result = $this->instance->uninstall($instance);
        $this->action->create('instance', $instance->id, 'uninstall', '', json_encode(array('result' => $result, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
        if($result->code == 200) return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'uninstallSuccess'), 'locate' => $this->createLink('space', 'browse')));

        return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'uninstallFail')));
    }

    /**
     * Start app instance.
     *
     * @param  int $instanceID
     * @access public
     * @return void
     */
    public function ajaxStart($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $result = $this->instance->start($instance);
        $this->action->create('instance', $instance->id, 'start', '', json_encode(array('result' => $result, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));

        if($result->code == 200) return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'startSuccess')));

        return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'startFail')));
    }

    /**
     * Stop app instance.
     *
     * @param  int $instanceID
     * @access public
     * @return void
     */
    public function ajaxStop($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $result = $this->instance->stop($instance);
        $this->action->create('instance', $instance->id, 'stop', '', json_encode(array('result' => $result, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
        if($result->code == 200) return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'stopSuccess')));

        return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'stopFail')));
    }

    /**
     * Query status of app instance.
     *
     * @access public
     * @return void
     */
    public function ajaxStatus()
    {
        $postData = fixer::input('post')->setDefault('idList', array())->get();

        $instances  = $this->instance->getByIdList($postData->idList);
        $statusList = $this->instance->batchFresh($instances);

        return $this->send(array('result' => 'success', 'data' => $statusList));
    }
}