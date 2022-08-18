<?php
/**
 * The create view of user module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     user
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php if(!empty($config->safe->mode)) $lang->user->placeholder->password1 = $lang->user->placeholder->passwordStrength[$config->safe->mode]?>
<?php js::set('holders', $lang->user->placeholder);?>
<?php js::set('roleGroup', $roleGroup);?>
<?php
$visionList     = $this->user->getVisionList();
$showVisionList = count($visionList) > 1;
?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><i class='icon icon-plus'></i> <?php echo $lang->user->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table align='center' class="table table-form">
        <?php $thClass = common::checkNotCN() ? 'w-enVerifyPassword' : 'w-verifyPassword';?>
        <tr>
          <th><?php echo $lang->user->account;?></th>
          <td><?php echo html::input('account', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <span class='input-group'>
              <?php echo html::password('password1', '', "class='form-control' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)'");?>
              <span class='input-group-addon' id='passwordStrength'></span>
            </span>
          </td>
          <td><?php echo $lang->user->placeholder->password1;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password2;?></th>
          <td><?php echo html::password('password2', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->realname;?></th>
          <td><?php echo html::input('realname', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->email;?></th>
          <td><?php echo html::input('email', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->gender;?></th>
          <td><?php echo html::radio('gender', (array)$lang->user->genderList, 'm');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->verifyPassword;?></th>
          <td class="required">
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center form-actions'>
            <?php if(!$showVisionList) echo html::hidden("visions[]", $this->config->vision);?>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
    <?php echo html::hidden('verifyRand', $rand);?>
  </div>
</div>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php include '../../common/view/footer.html.php';?>
