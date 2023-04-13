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
<?php js::set('copySuccess', $lang->system->copySuccess);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->devops->common;?></h2>
    </div>
    <table class='table table-form instance-status'>
      <tbody>
        <?php foreach($components as $category => $component):?>
        <tr>
          <th><?php echo $lang->system->devops->category[$category];?></th>
          <td>
            <?php $canVisit = $this->instance->canDo('visit', $component->instance);?>
            <?php echo html::a($this->instance->url($component->instance), $component->instance->name, '_blank', "class='btn btn-primary' title='{$component->instance->name}'". ($canVisit ? '' : ' disabled style="pointer-events: none;"'));?>
          <td></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="ossAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h2 class="text-center"><?php echo $lang->system->accountInfo;?></h2>
        <p><?php echo $lang->system->oss->user;?>：<span id='ossAdmin'></span></p>
        <p>
          <?php echo $lang->system->oss->password;?>：<input id='ossPassword' class='hidden' readonly />
          <?php echo html::commonButton($lang->system->copy, "id='copyPassBtn'", 'btn btn-link');?>
        </p>
        <div class="text-center">
          <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          <?php echo html::a('#', $lang->system->visit, '_blank', "id='ossVisitUrl' class='btn btn-primary btn-wide'");?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
