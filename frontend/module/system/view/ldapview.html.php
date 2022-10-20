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
<?php js::set('instanceIdList',  array($instanceID));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->LDAPInfo;?></h2>
      <div class='btn-toolbar pull-right'>
        <?php echo html::a(inLink('editLDAP', 'source=extra'), $lang->system->editLDAP, '', "class='btn-edit btn label label-outline label-primary label-lg'");?>
        <?php if($activeLDAP == 'qucheng'):?>
        <?php $this->system->printLDAPButtons($ldapInstance);?>
        <?php else:?>
        <?php echo html::a('#', $lang->system->visit, '', "disabled class='btn label label-outline label-primary label-lg'");?>
        <?php echo html::a('#', $lang->system->close, '', "disabled class='btn label label-outline label-primary label-lg'");?>
        <?php endif?>
      </div>
    </div>
    <?php if($activeLDAP == 'qucheng'):?>
    <table class='table table-form instance-status' instance-id='<?php echo $ldapInstance->id;?>' data-status='<?php echo $ldapInstance->status;?>'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->ldapSource;?></th>
          <td><?php echo zget($lang->system->ldapTypeList,  $activeLDAP, '');?></td>
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
    <?php elseif($activeLDAP == 'extra'):?>
    <table class='table table-form instance-status'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->ldapSource;?></th>
          <td><?php echo zget($lang->system->ldapTypeList, $activeLDAP, '');?></td>
          <td></td >
        </tr>
        <tr>
          <th><?php echo $lang->system->host;?></th>
          <td><?php echo zget($ldapSettings, 'host', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->port;?></th>
          <td><?php echo zget($ldapSettings, 'port', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->ldapUsername;?></th>
          <td><?php echo zget($ldapSettings, 'bindDN', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->password;?></th>
          <td><?php echo zget($ldapSettings, 'bindPass', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->ldapRoot;?></th>
          <td><?php echo zget($ldapSettings, 'baseDN', '');?></td>
        </tr>
        <tr><td></td></tr>
        <tr>
          <td></td>
          <th class='text-left'><?php echo $lang->system->ldapAdvance;?></th>
        </tr>
        <tr>
          <th><?php echo $lang->system->filterUser;?></th>
          <td><?php echo zget($ldapSettings, 'filter', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->email;?></th>
          <td><?php echo zget($ldapSettings, 'attrEmail', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->extraAccount;?></th>
          <td><?php echo zget($ldapSettings, 'attrUser', '');?></td>
        </tr>
      </tbody>
    </table>
    <?php endif?>
  </div>
</div>
<div class="modal fade" id="ldapAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h2 class="text-center"><?php echo $lang->system->accountInfo;?></h2>
        <div><?php echo $lang->system->account;?>：<span id='ldapAccount'></span></div>
        <div><?php echo $lang->system->password;?>：<span id='ldapPassword'></span></div>
      </div>
      <div class="text-center">
        <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
        <?php echo html::a('#', $lang->system->visit, '_blank', "id='ldapVisitUrl' class='btn btn-primary btn-wide'");?>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
