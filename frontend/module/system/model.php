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
     * Install LDAP.
     *
     * @param  string   $channel
     * @access public
     * @return bool|object
     */
    public function installLDAP($ldapApp, $channel)
    {
        $setting = fixer::input('post')->setDefault('source', 'qucheng')->get();

        if($setting->source == 'qucheng' && $setting->enableLDAP)
        {
            return $this->loadModel('instance')->installLDAP($ldapApp, '', 'OpenLDAP', $k8name = '', $channel);
        }

        dao::$errors[] = $this->lang->system->notSupportedLDAP;
        return false;
    }

    /**
     * Has installed global LDAP or not.
     *
     * @access public
     * @return bool
     */
    public function hasSystemLDAP()
    {
        $instanceID = $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=instanceID');
        return boolval($instanceID);
    }

    /**
     * Get global LDAP snippet name.
     *
     * @access public
     * @return string
     */
    public function ldapSnippetName()
    {
        return $this->loadModel('setting')->getItem('owner=system&module=common&section=ldap&key=snippetName');
    }
}

