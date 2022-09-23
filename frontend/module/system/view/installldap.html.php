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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->ldapManagement;?></h2>
    </div>
    <form id='installLDAP' class='cell load-indicator main-form form-ajax'>
      <h4>
        <?php echo html::checkbox('enableLDAP', array('true' => $lang->system->ldapEnabled), ''); ?>
      </h4>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th><?php echo $lang->system->ldapSource;?></th>
            <td><?php echo html::select('soruce', $lang->system->ldapTypeList, 'qucheng', "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->ldapUsername?></th>
            <td><?php echo $ldapApp->account->username;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->ldapRoot;?></th>
            <td><?php echo 'dc=quickon,dc=org';?></td>
          <tr>
        </tbody>
      </table>
      <div class="text-center form-actions"><?php echo html::submitButton($lang->system->ldapInstall);?></div>
    </form>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

