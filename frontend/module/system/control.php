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
        $this->view->title = $this->lang->system->common;

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
     * Test the connection of LDAP by post parameters.
     *
     * @access public
     * @return void
     */
    public function testLDAPConnection()
    {
        $settings = fixer::input('post')
            ->setDefault('host', '')
            ->setDefault('port', '')
            ->setDefault('bindDN', '')
            ->setDefault('bindPass', '')
            ->setDefault('baseDN', '')
            ->get();

        $success = $this->system->testLDAPConnection($settings);
        if($success) return $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->verifyLDAPSuccess));

        return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->verifyLDAPFailed));
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
            $postData = fixer::input('post')->setDefault('source', 'qucheng')->get();
            if($postData->source == 'qucheng')
            {
                $this->system->installQuchengLDAP($ldapApp, $channel);
            }
            else if($postData->source == 'extra')
            {
                $this->system->installExtraLDAP((object)$postData->extra);
            }
            else
            {
                dao::$errors[] = $this->lang->system->notSupportedLDAP;
                return false;
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->ldapInstallSuccess, 'locate' => $this->inLink('ldapView')));
        }

        $this->lang->switcherMenu = $this->system->getLDAPSwitcher();

        $this->view->title        = $this->lang->system->ldapManagement;
        $this->view->ldapApp      = $ldapApp;
        $this->view->activeLDAP   = $this->system->getActiveLDAP();
        $this->view->ldapSettings = $this->system->getExtraLDAPSettings();

        $this->display();
    }

    /**
     * Edit extra LDAP
     *
     * @access public
     * @return void
     */
    public function editLDAP()
    {
        $channel = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $ldapApp = $this->loadModel('store')->getAppInfoByChart('openldap', $channel, false);
        if($_POST)
        {
            $postData = fixer::input('post')->setDefault('source', 'qucheng')->get();
            if($postData->source == 'qucheng')
            {
                $this->system->updateQuchengLDAP($ldapApp, $channel);
            }
            else if($postData->source == 'extra')
            {
                $this->system->installExtraLDAP((object)$postData->extra, 'update');
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->ldapUpdateSuccess, 'locate' => $this->inLink('ldapView')));
        }

        $this->view->title = $this->lang->system->editLDAP;


        $this->view->ldapApp      = $ldapApp;
        $this->view->activeLDAP   = $this->system->getActiveLDAP();
        $this->view->ldapSettings = $this->system->getExtraLDAPSettings();
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

        $ldapInstance = new stdclass;
        $ldapInstance->id = 0;

        $activeLDAP = $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=active');
        if($activeLDAP == 'qucheng')
        {
            $instanceID   = $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=instanceID');
            $ldapInstance = $this->instance->getByID($instanceID);
            if(empty($ldapInstance)) return print js::alert($this->lang->system->notices->noLDAP);

            $ldapInstance = $this->instance->freshStatus($ldapInstance);
            $ldapSettings = json_decode($ldapInstance->ldapSettings);
        }
        else if($activeLDAP == 'extra')
        {
            $ldapSettings = $this->system->getExtraLDAPSettings();
        }
        else
        {
            return print js::alert($this->lang->system->notices->noLDAP);
        }

        $this->lang->switcherMenu = $this->system->getLDAPSwitcher();

        $this->view->title = $this->lang->system->ldapManagement;

        $this->view->activeLDAP   = $activeLDAP;
        $this->view->instanceID   = $ldapInstance->id;
        $this->view->ldapInstance = $ldapInstance;
        $this->view->ldapSettings = $ldapSettings;

        $this->display();
    }

    /**
     * Uninstall all LDAP in system. (This function is only for debug and test.)
     *
     * @access public
     * @return void
     */
    public function uninstallLDAP()
    {
        if(!$this->config->debug) return; // Only run in debug mode.

        /* 1. uninstall QuCheng LDAP. */
        $this->system->uninstallQuChengLDAP();

        /* 2. uninstall extra LDAP. */
        $this->system->uninstallExtraLDAP();

        echo date('Y-m-d H:i:s') . ': Uninstall LDAP success';
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
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('domain' => $ldapInstance->domain, 'account' => $ldapSetting->auth->username, 'pass' => $password)));
    }
}
