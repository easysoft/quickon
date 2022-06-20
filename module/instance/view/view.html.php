<?php
/**
 * The detail view file of instance module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     instance
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList',  array($instance->id));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left instance-name'>
    <h3><?php echo $instance->name;?></h3>
    <span><?php echo $instance->appVersion;?></span>
    <?php echo html::a($this->createLink('instance', 'editname', "id=$instance->id&onlybody=yes"), '<i class="icon-edit"></i>', '', "class='iframe' title='$lang->edit' data-width='600' data-app='space'");?>
  </div>
  <div class='btn-toolbar pull-right instance-panel'>
    <div class="btn-group">
      <?php $this->instance->printActions($instance);?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div>
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title"><?php echo $lang->instance->serviceInfo?></div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->instance->status;?></th>
                  <td class="instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
                    <?php echo $this->instance->printStatus($instance);?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->instance->source;?></th>
                  <td class="instance-source">
                    <span><?php echo zget($lang->instance->sourceList, $instance->source, '');?></span>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->instance->appTemplate;?></th>
                  <td><?php echo html::a($this->createLink('store', 'appView', "id=$instance->appID"), $instance->appName);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->instance->installAt;?></th>
                  <td><span><?php echo substr($instance->createdAt, 0, 16);?></span></td>
                </tr>
                <?php if($instance->status == 'running'):?>
                <tr>
                  <th><?php echo $lang->instance->runDuration;?></th>
                  <td><?php echo common::printDuration($instance->runDuration);?></td>
                </tr>
                <?php endif;?>
                <?php if($defaultAccount):?>
                <tr>
                  <th><?php echo $lang->instance->defaultAccount;?></th>
                  <td><?php echo $defaultAccount->username;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->instance->defaultPassword;?></th>
                  <td><?php echo $defaultAccount->password;?></td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel instance-log">
    <div class="panel-heading">
      <div class="panel-title"><?php echo $lang->instance->operationLog;?></div>
    </div>
    <div class="panel-body">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th class="w-150px"><?php echo $lang->instance->log->date;?></th>
            <th><?php echo $lang->instance->log->message;?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($logs as $log):?>
          <tr>
            <td><?php echo $log->date;?></td>
            <td><?php echo $this->instance->printLog($instance, $log);?></td>
            <td></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
