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
     * Get LDAP menu switcher.
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
     * Get Oss menu switcher.
     *
     * @access public
     * @return string
     */
    public function getOssSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->oss->common}</span>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Get SMTP menu switcher.
     *
     * @access public
     * @return string
     */
    public function getSMTPSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->SMTP->common}</span>";
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
    public function printDBAction($db)
    {
        $disabled = strtolower($db->status) == 'running' ? '' : 'disabled';
        $btnHtml  = html::commonButton($this->lang->system->management, "{$disabled} data-db-name='{$db->name}' data-db-type='{$db->db_type}' data-namespace='{$db->namespace}'", 'db-login btn btn-primary');

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

    /**
     * Update Qucheng LDAP.
     *
     * @param  object $ldapApp
     * @param  string $channel
     * @access public
     * @return string
     */
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
     * @access protected
     * @return bool
     */
    public function installExtraLDAP($settings)
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
        $snippetSettings->values->auth->ldap->port      = strval($settings->port);
        $snippetSettings->values->auth->ldap->bindDN    = "cn={$settings->bindDN},{$settings->baseDN}";
        $snippetSettings->values->auth->ldap->bindPass  = $settings->bindPass;
        $snippetSettings->values->auth->ldap->baseDN    = $settings->baseDN;
        $snippetSettings->values->auth->ldap->filter    = html_entity_decode($settings->filter);
        $snippetSettings->values->auth->ldap->attrUser  = $settings->attrUser;
        $snippetSettings->values->auth->ldap->attrEmail = $settings->attrEmail;

        $exists = $this->getExtraLDAPSettings();
        if(empty($exists))
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
        $secretKey          = helper::readKey();
        $encryptedPassword  = openssl_encrypt($snippetSettings->values->auth->ldap->bindPass, 'DES-ECB', $secretKey);
        $settings->bindPass = $encryptedPassword;

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
            dao::$errors[] = $this->lang->system->errors->LDAPLinked;
            return false;
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=ldap&key=instanceID');
        $instance   = $this->loadModel('instance')->getByID($instanceID);
        if($instance)
        {
            /* 1. Uninstall QuCheng LDAP service. */
            if(!$this->loadModel('instance')->uninstall($instance))
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
                return false;
            }

            /* 2. Remove snippet config map from CNE. */
            $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

            $apiParams = new stdclass;
            $apiParams->name      = 'snippet-qucheng-ldap';
            $apiParams->namespace = $space->k8space;

            $result = $this->loadModel('cne')->removeSnippet($apiParams);
            if($result->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
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
            dao::$errors[] = $this->lang->system->errors->LDAPLinked;
            return false;
        }

        /* 1. Remove snippet config map from CNE. */
        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $apiParams = new stdclass;
        $apiParams->name      = 'snippet-extra-ldap';
        $apiParams->namespace = $space->k8space;

        $result = $this->loadModel('cne')->removeSnippet($apiParams);
        if($result->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToDeleteLDAPSnippet;
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
     * @return object|array
     */
    public function getExtraLDAPSettings()
    {
        $settings = $this->setting->getItem('owner=system&module=common&section=ldap&key=extraSettings');
        $settings = @json_decode($settings);
        if(empty($settings)) return array();

        $secretKey          = helper::readKey();
        $settings->bindPass = openssl_decrypt($settings->bindPass, 'DES-ECB', $secretKey);
        return $settings;
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

    /**
     * Check global SMTP is enabled or not.
     *
     * @access public
     * @return bool
     */
    public function isSMTPEnabled()
    {
        $smtpSnippetName = $this->smtpSnippetName();
        $enabled         = $this->setting->getItem('owner=system&module=common&section=smtp&key=enabled');

        return $smtpSnippetName && $enabled;
    }

    /**
     * Get SMTP snippet name.
     *
     * @access public
     * @return string
     */
    public function smtpSnippetName()
    {
        return $this->setting->getItem('owner=system&module=common&section=smtp&key=snippetName');
    }

    /**
     * Get SMTP settings.
     *
     * @access public
     * @return object
     */
    public function getSMTPSettings()
    {
        $settingMap = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=settingsMap'));
        if(empty($settingMap)) return new stdclass;

        $settings = $settingMap->env;
        $settings->SMTP_PASS = openssl_decrypt($settings->SMTP_PASS, 'DES-ECB', helper::readKey());

        //$snippetSettings = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=snippetSettings'));

        return $settings;
    }

    public function updateSMTPSettings()
    {
        $this->loadModel('cne');
        $this->loadModel('instance');

        $smtpSettings = fixer::input('pos')->get();

        $instanceID = $this->setting->getItem('owner=system&module=common&section=smtp&key=instanceID');
        $instance   = $this->instance->getByID($instanceID);
        if($instance)
        {

        }

        /* 1. Update SMTP service settings. */
        $settingsMap = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=settingsMap'));

        $settingsMap->env->SMTP_HOST         = $smtpSettings->host;
        $settingsMap->env->SMTP_PORT         = strval($smtpSettings->port);
        $settingsMap->env->SMTP_USER         = $smtpSettings->user;
        $settingsMap->env->SMTP_PASS         = $smtpSettings->pass;
        //$settingsMap->env->AUTHENTICATE_CODE = helper::randStr(24);

        $this->

        /* 2. Update SMTP snippet settings. */
        $snippetSettings = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=snippetSettings'));

        $snippetSettings->values->mail->smtp->user = $settingsMap->env->SMTP_USER;
        $snippetSettings->values->mail->smtp->pass = $settingsMap->env->AUTHENTICATE_CODE;

        $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
        if($snippetResult->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallSMTP;
            return false;
        }

        /* Save LDAP account. */
        $secretKey = helper::readKey();
        $settingsMap->env->SMTP_PASS         = openssl_encrypt($settingsMap->env->SMTP_PASS, 'DES-ECB', $secretKey);
        $settingsMap->env->AUTHENTICATE_CODE = openssl_encrypt($settingsMap->env->AUTHENTICATE_CODE, 'DES-ECB', $secretKey);

        $snippetSettings->values->mail->smtp->pass = $settingsMap->env->AUTHENTICATE_CODE;

        $this->loadModel('setting');
        $this->setting->setItem('system.common.smtp.enabled', true);
        $this->setting->setItem('system.common.smtp.instanceID', $instance->id);
        $this->setting->setItem('system.common.smtp.snippetName', $snippetSettings->name);
        $this->setting->setItem('system.common.smtp.settingsMap', json_encode($settingsMap));
        $this->setting->setItem('system.common.smtp.snippetSettings', json_encode($snippetSettings));

        $this->loadModel('cne')->updateSnippet();

        return true;
    }

    /**
     * Install global SMTP service.
     *
     * @param  string $channel
     * @access public
     * @return bool
     */
    public function installSysSMTP($channel = 'stable')
    {
        $settings = fixer::input('post')->get();

        $smtpApp = $this->loadModel('store')->getAppInfoByChart('cne-courier', $channel, false);
        if(empty($smtpApp))
        {
            dao::$errors[] = $this->lang->system->notFoundSMTPApp;
            return false;
        }

        $result = $this->loadModel('instance')->installSysSMTP($smtpApp, $settings, 'cne-courier', '', $channel);
        return $result;
    }

    /**
     * Uninstall system SMTP.
     *
     * @access public
     * @return bool
     */
    public function uninstallSysSMTP()
    {
        $smtpLinked = $this->loadModel('instance')->countSMTP();
        if($smtpLinked)
        {
            dao::$errors[] = $this->lang->system->errors->SMTPLinked;
            return false;
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=smtp&key=instanceID');
        $instance   = $this->instance->getByID($instanceID);
        if($instance)
        {
            /* 1. Uninstall QuCheng LDAP service. */
            if(!$this->loadModel('instance')->uninstall($instance))
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallSMTP;
                return false;
            }

            /* 2. Remove snippet config map from CNE. */
            $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

            $apiParams = new stdclass;
            $apiParams->name      = 'snippet-smtp-proxy';
            $apiParams->namespace = $space->k8space;

            $result = $this->loadModel('cne')->removeSnippet($apiParams);
            if($result->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
                return false;
            }
        }

        /* 3. Delete SMTP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=enabled');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=instanceID');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=snippetName');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=settingsMap');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=snippetSettings');

        return true;
    }

    /**
     * Get customized domain settings. *
     * @access public
     * @return object
     */
    public function getDomainSettings()
    {
        $settings = new stdclass;
        $settings->customDomain = $this->setting->getItem('owner=system&module=common&section=domain&key=customDomain');
        $settings->https        = $this->setting->getItem('owner=system&module=common&section=domain&key=https');
        $settings->publicKey    = $this->setting->getItem('owner=system&module=common&section=domain&key=publicKey');
        $settings->privateKey   = $this->setting->getItem('owner=system&module=common&section=domain&key=privateKey');

        return $settings;
    }

    /**
     * Save customized somain settings.
     *
     * @access public
     * @return void
     */
    public function saveDomainSettings()
    {
        $settings = fixer::input('post')
            ->setDefault('customDomain', '')
            ->setDefault('https', 'false')
            ->setIf(is_array($this->post->https) && in_array('true', $this->post->https), 'https', 'true')
            ->setDefault('publicKey', '')
            ->setDefault('privateKey', '')
            ->get();

        $this->dao->from('system')->data($settings)
            ->check('customDomain', 'notempty');
            //->check('publicKey', '', )
            //->check('privateKey', '', );
        if(dao::isError()) return;

        if(!validater::checkREG($settings->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            dao::$errors[] = $this->lang->system->errors->invalidDomain;
            return;
        }

        $oldSettings = $this->getDomainSettings();
        if($settings->customDomain == $oldSettings->customDomain)
        {
            dao::$errors[] = $this->lang->system->errors->newDomainIsSameWithOld;
            return;
        }

        if(stripos($settings->customDomain, 'haogs.cn') !== false)
        {
            dao::$errors[] = $this->lang->system->errors->forbiddenOriginalDomain;
            return;
        }

        $expiredDomain   = $this->setting->getItem('owner=system&module=common&section=domain&key=expiredDomain');
        $expiredDomain   = empty($expiredDomain ) ? array() : json_decode($expiredDomain, true);
        $expiredDomain[] = zget($settings, 'customDomain', '');
        $this->setting->setItem('system.common.domain.expiredDomain', json_encode($expiredDomain));

        $this->setting->setItem('system.common.domain.customDomain', zget($settings, 'customDomain', ''));
        $this->setting->setItem('system.common.domain.https', zget($settings, 'https', 'false'));
        $this->setting->setItem('system.common.domain.publicKey', zget($settings, 'publicKey', ''));
        $this->setting->setItem('system.common.domain.privateKey', zget($settings, 'privateKey', ''));

        $this->loadModel('instance')->updateInstancesDomain();

        $this->updateMinioDomain();
    }

    /**
     * Update minio domain.
     *
     * @access public
     * @return void
     */
    public function updateMinioDomain()
    {
        $this->loadModel('cne');
        $sysDomain = $this->cne->sysDomain();

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->chart     = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = 'cne-system';

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->minio = new stdclass;
        $settings->settings_map->minio->ingress = new stdclass;
        $settings->settings_map->minio->ingress->enabled = true;
        $settings->settings_map->minio->ingress->host    = 's3.' . $sysDomain;

        //$settings->settings_map->global = new stdclass;
        //$settings->settings_map->global->ingress = $settings->settings_map->ingress;

        $this->cne->updateConfig($minioInstance, $settings);
    }

    /**
     * Print edit SMTP button.
     *
     * @access public
     * @return string
     */
    public function printEditSMTPBtn()
    {
        $this->loadModel('instance');

        $disableEdit = false;
        $title       = $this->lang->system->SMTP->edit;
        $toolTips    = '';
        $count       = $this->instance->countSMTP();
        if($count)
        {
            $disableEdit = true;
            $title       = $this->lang->system->notices->smtpUsed;
            $toolTips    = "data-toggle='tooltip' data-placement='bottom'";
        }

        $buttonHtml = '';
        $buttonHtml .= "<span class='edit-tools-tips' {$toolTips} title='{$title}'>";
        $buttonHtml .= html::a(inLink('editSMTP'), $this->lang->system->SMTP->edit, '', ($disableEdit ? 'disabled' : '') . " title='{$title}' class='btn-edit btn label label-outline label-primary label-lg'");
        $buttonHtml .= "</span>";

        echo $buttonHtml;
    }

    /**
     * Print SMTP buttons.
     *
     * @param  objevt $smtpInstance
     * @access public
     * @return string
     */
    public function printSMTPButtons($smtpInstance)
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $buttonHtml = '';

        $disableStart = !$this->instance->canDo('start', $smtpInstance);
        $buttonHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$smtpInstance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $title    = $this->lang->instance->stop;
        $toolTips = '';
        $count    = $this->instance->countSMTP();
        if($count)
        {
            $title    = $this->lang->system->notices->smtpUsed;
            $toolTips = "data-toggle='tooltip' data-placement='bottom' runat='server'";
        }

        $disableStop = $count > 0 || !$this->instance->canDo('stop', $smtpInstance);
        $buttonHtml .= "<span {$toolTips} title='{$title}'>";
        $buttonHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$smtpInstance->id}' title='{$title}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-danger label-lg');
        $buttonHtml .= "</span>";

        echo $buttonHtml;

    }
}

