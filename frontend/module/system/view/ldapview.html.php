<?php
/**
 * The install ldap view file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList',  array($ldapInstance->id));?>
<?php js::set('demoAppLife',     $config->demoAppLife);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->ldapManagement;?></h2>
      <div class='btn-toolbar pull-right'>
        <?php $this->system->printLDAPButtons($ldapInstance);?>
      </div>
    </div>
    <table class='table table-form instance-status' instance-id='<?php echo $ldapInstance->id;?>' data-status='<?php echo $ldapInstance->status;?>'>
        <tbody>
          <tr>
            <th><?php echo $lang->system->ldapSource;?></th>
            <td><?php echo zget($lang->system->ldapTypeList, 'qucheng');?></td>
            <td></td >
          </tr>
          <tr>
            <th><?php echo $lang->system->ldapUsername?></th>
            <td><?php echo zget($ldapSettings->auth, 'username', '');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->ldapRoot;?></th>
            <td><?php echo zget($ldapSettings->auth, 'root', '');?></td>
          <tr>
        </tbody>
      </table>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

