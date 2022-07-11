<?php
/**
 * The view file of backup module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     bakcup
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!empty($error)):?>
<div id="notice" class='alert alert-success' style="margin-bottom:35px;">
  <div class="content"><i class='icon-exclamation-sign'></i> <?php echo $error;?></div>
</div>
<?php endif;?>
<div id='mainContent' class="main-row">
  <div class="main-col main-content">
    <div class='main-header'>
      <h2 style="font-size:20px;">
        <?php echo $lang->backup->systemInfo;?>
      </h2>
    </div>
    <table class='table table-condensed table-bordered active-disabled table-fixed'>
      <thead class="text-center">
        <tr class='info-header'>
          <th class='w-150px'><?php echo $lang->backup->name;?></th>
          <th class='w-150px'><?php echo $lang->backup->status;?></th>
          <th class='w-100px'><?php echo $lang->backup->currentVersion;?></th>
          <th class='w-100px'><?php echo $lang->backup->latestVersion;?></th>
          <th class='actionWidth'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <tr class='info-row'>
          <td><?php echo $lang->quchengPlatform;?></td>
          <td><?php echo $lang->backup->running;?></td>
          <td><?php echo getenv('APP_VERSION');?></td>
          <td>
            <span><?php echo $this->session->platformLatestVersion->app_version;?></span>
            <?php echo html::a($config->backup->versionURL, "<i class='icon icon-info-sign' style='color: #4E83F0;'></i>", '_blank', "title='{$lang->backup->versionInfo}'");?>
          </td>
          <td>
            <?php echo version_compare($this->session->platformLatestVersion->version, $this->config->platformVersion, '>') ?  html::commonButton($lang->backup->upgrade, '', 'btn btn-link upgrade', 'upload') : '';?>
            <?php echo html::a(inlink('backup', "reload=yes"), "<i class='icon icon-refresh'></i> " . $lang->backup->backup, 'hiddenwin', "class='backup'");?>
            <?php //echo html::commonButton($lang->backup->shortCommon, '', 'btn btn-link', 'sync');?>
            <?php //echo html::commonButton($lang->backup->rollback, '', 'btn btn-link', 'history');?>
            <?php //echo html::commonButton($lang->backup->restart, '', 'btn btn-link', 'off');?>
          </td>
        </tr>
      </tbody>
    </table>
    <div class='main-header'>
      <h2 style="font-size:20px;">
        <?php echo $lang->backup->history;?>
      </h2>
      <div class='pull-right'>
        <?php common::printLink('backup', 'setting', '', "<i class='icon icon-cog'></i> " . $lang->backup->setting, '', "data-width='500' class='iframe btn btn-primary'");?>
      </div>
    </div>
    <table class='table table-condensed table-bordered active-disabled table-fixed'>
      <thead class="text-center">
        <tr class='history-header'>
          <th class='w-150px'><?php echo $lang->backup->time?></th>
          <th class='w-100px'><?php echo $lang->backup->backupPerson?></th>
          <th class='w-100px'><?php echo $lang->backup->type?></th>
          <th class='w-140px'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
      <?php foreach($backups as $backupFile):?>
        <tr class='history-row'>
          <td><?php echo date(DT_DATETIME1, $backupFile->time);?></td>
          <td>
            <?php echo $backupFile->sqlSummary['account'];?>
          </td>
          <td style='overflow:visible'>
            <?php echo zget($lang->backup->typeList, $backupFile->sqlSummary['backupType']);?>
          </td>
          <td>
            <?php
            if(common::hasPriv('backup', 'restore')) echo html::a(inlink('restore', "file={$backupFile->name}&confirm=yes"), "<i class='icon-history'></i> " . $lang->backup->restore, 'hiddenwin', "class='btn btn-link'");
            if(common::hasPriv('backup', 'delete')) echo html::a(inlink('delete', "file=$backupFile->name"),  "<i class='icon-trash'></i> " . $lang->delete, 'hiddenwin', "class='btn btn-link'");
            ?>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <h2>
      <span class='label label-info'>
      <?php echo $lang->backup->restoreTip;?>
      <?php //printf($lang->backup->holdDays, $config->backup->holdDays)?>
      </span>
    </h2>
  </div>
</div>
<div class="modal fade" id="waitting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <p><?php echo $lang->backup->waitting?></p>
        <div id='message'><?php echo sprintf($lang->backup->progressSQL, 0);?></div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="upgradeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h2 class="text-center"><?php echo $lang->backup->upgrade;?></h2>
        <div class="text-center confirm-text"><?php echo $lang->backup->confirmUpgrade;?></div>
      </div>
      <div class="text-center">
        <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
        <?php echo html::commonButton($lang->backup->upgrade, "id='submitUpgrade'", 'btn btn-primary btn-wide');?>
      </div>
    </div>
  </div>
</div>
<?php js::set('backup', $lang->backup->common);?>
<?php js::set('rmPHPHeader', $lang->backup->rmPHPHeader);?>
<?php js::set('confirmRestore', $lang->backup->confirmRestore);?>
<?php js::set('restore', $lang->backup->restore);?>
<?php include '../../common/view/footer.html.php';?>
