<?php
/**
 * The model file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class systemModel extends model
{
    /**
     * Construct function: load setting model.
     *
     * @access public
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
    }

    /**
     * Get switcher of LDAP view page of store.
     *
     * @access public
     * @return string
     */
    public function getLDAPSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->ldapManagement}</span>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Print action buttons.
     *
     * @param  object $db
     * @access public
     * @return void
     */
    public function printAction($db)
    {
        $disabled = strtolower($db->status) == 'running' ? '' : 'disabled';
        $btnHtml  = html::commonButton($this->lang->system->management, "{$disabled} data-db-name='{$db->name}' data-namespace='{$db->namespace}'", 'db-login btn btn-primary');

        echo $btnHtml;
    }

    public function printEditLDAPBtn()
    {
        $this->loadModel('instance');

        $disableEdit = false;
        $title       = $this->lang->system->editLDAP;
        $toolTips    = '';
        $count       = $this->instance->countLDAP();
        if($count)
        {
            $disableEdit = true;
            $title       = $this->lang->system->notices->ldapUsed;
            $toolTips    = "data-toggle='tooltip' data-placement='bottom'";
        }

        $buttonHtml = '';
        $buttonHtml .= "<span class='edit-tools-tips' {$toolTips} title='{$title}'>";
        $buttonHtml .= html::a(inLink('editLDAP'), $this->lang->system->editLDAP, '', ($disableEdit ? 'disabled' : '') . " title='{$title}' class='btn-edit btn label label-outline label-primary label-lg'");
        $buttonHtml .= "</span>";

        echo $buttonHtml;
    }
    /**
     * Print LDAP buttons.
     *
     * @param  objevt $ldapInstance
     * @access public
     * @return mixed
     */
    public function printLDAPButtons($ldapInstance)
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $buttonHtml = '';

        if($ldapInstance->domain)
        {
            $disableVisit = !$this->instance->canDo('visit', $ldapInstance);
            $buttonHtml  .= html::commonButton($this->lang->instance->visit, "instance-id='{$ldapInstance->id}' title='{$this->lang->instance->visit}'" . ($disableVisit ? ' disabled ' : ''), 'btn-visit btn label label-outline label-primary label-lg');
        }

        $disableStart = !$this->instance->canDo('start', $ldapInstance);
        $buttonHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$ldapInstance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $title    = $this->lang->instance->stop;
        $toolTips = '';
        $count    = $this->instance->countLDAP();
        if($count)
        {
            $title    = $this->lang->system->notices->ldapUsed;
            $toolTips = "data-toggle='tooltip' data-placement='bottom' runat='server'";
        }

        $disableStop = $count > 0 || !$this->instance->canDo('stop', $ldapInstance);
        $buttonHtml .= "<span {$toolTips} title='{$title}'>";
        $buttonHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$ldapInstance->id}' title='{$title}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-danger label-lg');
        $buttonHtml .= "</span>";

        echo $buttonHtml;

    }

    /**
     * Install QuCheng LDAP.
     *
     * @param  string   $channel
     * @access public
     * @return bool|object
     */
    public function installQuchengLDAP($ldapApp, $channel)
    {
        return $this->loadModel('instance')->installLDAP($ldapApp, '', 'OpenLDAP', $k8name = '', $channel);
    }

    public function updateQuchengLDAP($ldapApp, $channel)
    {
        $instanceID = $this->setting->getItem('owner=system&module=common&section=ldap&key=instanceID');
        if($instanceID)
        {
            /* If QuCheng internal LDAP has been installed. Set QuCheng LDAP to be active. */
            return $this->setting->setItem('system.common.ldap.active', 'qucheng');
        }
        else
        {
            /* If QuCheng internal LDAP has not been installed. */
            return $this->installQuchengLDAP($ldapApp, $channel);
        }
    }

    /**
     * Install or update extra LDAP: it is creating a snippet in k8s system in fact.
     *
     * @param  object    $settings
     * @param  string    $action install, update
     * @access protected
     * @return bool
     */
    public function installExtraLDAP($settings, $action='install')
    {
        if(!$this->testLDAPConnection($settings))
        {
            dao::$errors[] = $this->lang->system->notSupportedLDAP;
            return false;
        }

        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $snippetSettings = new stdclass;
        $snippetSettings->name        = 'snippet-extra-ldap';
        $snippetSettings->namespace   = $space->k8space;
        $snippetSettings->auto_import = false;

        $snippetSettings->values = new stdclass;
        $snippetSettings->values->auth = new stdclass;
        $snippetSettings->values->auth->ldap = new stdclass;
        $snippetSettings->values->auth->ldap->enabled   = true;
        $snippetSettings->values->auth->ldap->type      = 'ldap';
        $snippetSettings->values->auth->ldap->host      = $settings->host;
        $snippetSettings->values->auth->ldap->port      = intval($settings->port);
        $snippetSettings->values->auth->ldap->bindDN    = "cn={$settings->bindDN},{$settings->baseDN}";
        $snippetSettings->values->auth->ldap->bindPass  = $settings->bindPass;
        $snippetSettings->values->auth->ldap->baseDN    = $settings->baseDN;
        $snippetSettings->values->auth->ldap->filter    = html_entity_decode($settings->filter);
        $snippetSettings->values->auth->ldap->attrUser  = $settings->attrUser;
        $snippetSettings->values->auth->ldap->attrEmail = $settings->attrEmail;

        if($action == 'install')
        {
            $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
            if($snippetResult->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToInstallExtraLDAP;
                return false;
            }
        }
        else
        {
            $snippetResult = $this->loadModel('cne')->updateSnippet($snippetSettings);
            if($snippetResult->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUpdateExtraLDAP;
                return false;
            }
        }

        /* Save extra LDAP setting to database. */
        $this->setting->setItem('system.common.ldap.active', 'extra');
        $this->setting->setItem('system.common.ldap.extraSnippetName', $snippetSettings->name); // Parameter for App installation API.
        $this->setting->setItem('system.common.ldap.extraSettings', json_encode($settings));

        return true;
    }

    /**
     * Uninstall QuCheng LDAP.
     *
     * @access public
     * @return bool
     */
    public function uninstallQuChengLDAP()
    {
        $ldapLinked = $this->loadModel('instance')->countLDAP();
        if($ldapLinked)
        {
            dao::$errors[] = $this->system->errors->LDAPLinkded;
            return false;
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=ldap&key=instanceID');
        $instance   = $this->loadModel('instance')->getByID($instanceID);
        if($instance)
        {
            /* 1. Uninstall QuCheng LDAP service. */
            if(!$this->loadModel('instance')->uninstall($instance))
            {
                dao::$errors[] = $this->system->errors->failToUninstallQuChengLDAP;
                return false;
            }

            /* 2. Remove snippet config map from CNE. */
            $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

            $apiParams = new stdclass;
            $apiParams->name      = $this->ldapSnippetName();
            $apiParams->namespace = $space->k8space;

            $result = $this->loadModel('cne')->removeSnippet($apiParams);
            if($result->code != 200)
            {
                dao::$errors[] = $this->system->errors->failToUninstallQuChengLDAP;
                return false;
            }
        }

        /* 3. Delete LDAP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=instanceID');
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=snippetName');
        if($this->getActiveLDAP() == 'qucheng') $this->setting->deleteItems('owner=system&module=common&section=ldap&key=active');

        return true;
    }

    /**
     * Uninstall extra LDAP.
     *
     * @access public
     * @return bool
     */
    public function uninstallExtraLDAP()
    {
        $ldapLinked = $this->loadModel('instance')->countLDAP();
        if($ldapLinked)
        {
            dao::$errors[] = $this->system->errors->LDAPLinkded;
            return false;
        }

        /* 1. Remove snippet config map from CNE. */
        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $apiParams = new stdclass;
        $apiParams->name      = $this->ldapSnippetName();
        $apiParams->namespace = $space->k8space;

        $result = $this->loadModel('cne')->removeSnippet($apiParams);
        if($result->code != 200)
        {
            dao::$errors[] = $this->system->errors->failToUninstallExtraLDAP;
            return false;
        }

        /* 2. Delete extra LDAP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=extraSettings');
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=extraSnippetName');
        if($this->getActiveLDAP() == 'extra') $this->setting->deleteItems('owner=system&module=common&section=ldap&key=active');

        return true;
    }

    /**
     * Get extra LDAP settings.
     *
     * @access public
     * @return object
     */
    public function getExtraLDAPSettings()
    {
        $settings = $this->setting->getItem('owner=system&module=common&section=ldap&key=extraSettings');
        $settings = @json_decode($settings);
        return $settings ? $settings : new stdclass;
    }

    /**
     * Test LDAP Connection by post settings.
     *
     * @param  object $settings
     * @access public
     * @return bool
     */
    public function testLDAPConnection($settings)
    {
        $connectID = ldap_connect("ldap://{$settings->host}:{$settings->port}");

        if(!ldap_set_option($connectID, LDAP_OPT_PROTOCOL_VERSION, 3)) return false;

        return ldap_bind($connectID, "cn={$settings->bindDN},{$settings->baseDN}", $settings->bindPass);
    }

    /**
     * Has installed global LDAP or not.
     *
     * @access public
     * @return bool
     */
    public function hasSystemLDAP()
    {
        $activeLDAP = $this->setting->getItem('owner=system&module=common&section=ldap&key=active');
        return $activeLDAP == 'extra' or $activeLDAP == 'qucheng'; // LDAP has been installed.
    }

    /**
     * Get global LDAP snippet name.
     *
     * @access public
     * @return string
     */
    public function ldapSnippetName()
    {
        $activeLDAP = $this->setting->getItem('owner=system&module=common&section=ldap&key=active');

        if($activeLDAP == 'extra') return $this->setting->getItem('owner=system&module=common&section=ldap&key=extraSnippetName');

        return $this->setting->getItem('owner=system&module=common&section=ldap&key=snippetName');
    }

    /**
     * Get active LDAP type.
     *
     * @access public
     * @return string|null
     */
    public function getActiveLDAP()
    {
        return $this->setting->getItem('owner=system&module=common&section=ldap&key=active');
    }
}

