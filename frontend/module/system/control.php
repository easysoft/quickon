<?php
/**
 * The control file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class system extends control
{
    /**
     * System index.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->display();
    }

    /**
     * Show database list.
     *
     * @access public
     * @return void
     */
    public function dbList()
    {
        $this->app->loadLang('instance');

        $this->view->title  = $this->lang->system->dbManagement;
        $this->view->dbList = $this->loadModel('cne')->allDBList();

        $this->display();
    }

    /**
     * Install LDAP
     *
     * @access public
     * @return void
     */
    public function installLDAP()
    {
        if($this->system->hasSystemLDAP()) return print(js::locate($this->inLink('ldapView')));

        $channel = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $ldapApp = $this->loadModel('store')->getAppInfoByChart('openldap', $channel, false);
        if($_POST)
        {
            $this->system->installLDAP($ldapApp, $channel);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->ldapInstallSuccess, 'locate' => $this->inLink('ldapView')));
        }

        $this->lang->switcherMenu = $this->system->getLDAPSwitcher();

        $this->view->title   = $this->lang->system->ldapManagement;
        $this->view->ldapApp = $ldapApp;
        $this->display();
    }

    /**
     * LDAP view.
     *
     * @access public
     * @return void
     */
    public function ldapView()
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $instanceID   = $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=instanceID');
        $ldatInstance = $this->instance->getByID($instanceID);
        if(empty($ldatInstance)) return print js::alert($this->lang->system->notices->noLDAP);

        $ldatInstance = $this->instance->freshStatus($ldatInstance);

        $this->lang->switcherMenu = $this->system->getLDAPSwitcher();

        $this->view->title = $this->lang->system->ldapManagement;

        $this->view->ldapInstance = $ldatInstance;
        $this->view->ldapSettings = json_decode($ldatInstance->ldapSettings);

        $this->display();
    }

    /**
     * Generate database auth parameters and jump to login page.
     *
     * @access public
     * @return void
     */
    public function ajaxDBAuthUrl()
    {
        $post = fixer::input('post')
            ->setDefault('namespace', 'default')
            ->get();
        if(empty($post->dbName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->dbNameIsEmpty));

        $detail = $this->loadModel('cne')->dbDetail($post->dbName, $post->namespace);
        if(empty($detail)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->notFoundDB));

        $dbAuth = array();
        $dbAuth['server']   = $detail->host . ':' . $detail->port;
        $dbAuth['username'] = $detail->username;
        $dbAuth['db']       = $detail->database;
        $dbAuth['password'] = $detail->password;

        $url = '/adminer?'. http_build_query($dbAuth);
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('url' => $url)));
    }

    /**
     * Get LDAP info by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxLdapInfo()
    {
        $instanceID   = $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=instanceID');
        $ldapInstance = $this->loadModel('instance')->getByID($instanceID);
        if(empty($ldapInstance)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->notFoundLDAP));

        $ldapSetting = json_decode($ldapInstance->ldapSettings);

        $password = openssl_decrypt($ldapSetting->auth->password, 'DES-ECB', $ldapInstance->createdAt);
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('url' => $ldapInstance->domain, 'pass' => $password)));
    }
}

