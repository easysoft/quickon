<?php
/**
 * The control file of navigation module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Xin Zhou <zhouxin@easycorp.ltd>
 * @package   navigation
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class navigation extends control
{
    /**
     * Create a app.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->navigation->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->display();
    }

    /**
     * Edit a app.
     *
     * @param  int    $id
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function edit($id, $type)
    {
        $oldApp = $this->navigation->getByID($id, $type);

        if($_POST)
        {
            $this->navigation->update($id, $type);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->id     = $id;
        $this->view->type   = $type;
        $this->view->oldApp = $oldApp;
        $this->display();
    }

    /**
     * The main page of navigation.
     *
     * @access public
     * @return void
     */
    public function browse()
    {
        $this->loadModel('instance');

        $account = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $user    = isset($this->app->user->account) ? $this->app->user->account : 'anonymous';

        $hideInaccessible = $this->navigation->getSetting('hideInaccessible');
        $status = $hideInaccessible->value == 'on' ? 'running' : 'all';

        $instances       = $this->instance->getByAccount($account);
        $pinnedInstances = $this->instance->getByAccount($account, '', true, '', $status);
        $apps            = $this->navigation->getApps();
        $pinnedApps      = $this->navigation->getApps(true);

        $this->view->title           = $this->lang->navigation->common;
        $this->view->instances       = $instances;
        $this->view->pinnedInstances = $pinnedInstances;
        $this->view->apps            = $apps;
        $this->view->pinnedApps      = $pinnedApps;
        $this->view->user            = $user;

        $this->display();
    }

    /**
     * Ajax get pinned instances and pinned apps.
     *
     * @param  int    $id
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function ajaxGetPinnedInstance($id, $objectType = 'instance')
    {
        $this->loadModel('instance');
        if($objectType == 'app')
        {
            $this->navigation->pinToggle($id);
        }
        else
        {
            $this->instance->pinToggle($id);
        }
        $account = $this->app->user->account;

        $hideInaccessible = $this->navigation->getSetting('hideInaccessible');
        $status = $hideInaccessible->value == 'on' ? 'running' : 'all';

        $this->view->pinnedInstances = $this->instance->getByAccount($account, '', true, '', $status);
        $this->view->pinnedApps      = $this->navigation->getApps(true);

        $this->view->showAddItem = true;
        $this->display();
    }

    /**
     * Ajax search pinned instances and pinned apps.
     *
     * @param  string $name
     * @access public
     * @return void
     */
    public function ajaxSearchPinnedInstance($name = '')
    {
        $name = base64_decode(trim($name));
        $this->loadModel('instance');
        $account = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $user    = isset($this->app->user->account) ? $this->app->user->account : 'anonymous';

        $hideInaccessible = $this->navigation->getSetting('hideInaccessible');
        $status = $hideInaccessible->value == 'on' ? 'running' : 'all';

        $this->view->pinnedInstances = $this->instance->getByAccount($account, '', true, $name, $status);
        $this->view->pinnedApps      = $this->navigation->getApps(true, $name);

        $this->view->user        = $user;
        $this->view->showAddItem = false;
        $this->display('navigation', 'ajaxGetPinnedInstance');
    }

    /**
     * The settings page of navigation.
     *
     * @access public
     * @return void
     */
    public function settings()
    {
        $this->view->settings = $this->navigation->getSettings();
        $this->display();
    }

    /**
     * Configure settings for navigation.
     *
     * @param  string    $field
     * @access public
     * @return void
     */
    public function configure($field)
    {
        if($field == 'backgroundImage' and $_FILES)
        {
            $fileTitles = $this->loadModel('file')->saveUpload('navBackground', '', '', 'backgroundImage');
            $imageInfo  = $this->loadModel('file')->getById(key($fileTitles));
            $this->session->set('backgroundImage', $imageInfo->webPath);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('settings')));
        }
        if($_POST)
        {
            $this->navigation->configure($field);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('settings')));
        }

        $setting = $this->navigation->getSetting($field);

        $this->view->field = $field;
        $this->view->value = $setting->value;
        $this->display();
    }
}
