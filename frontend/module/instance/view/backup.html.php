<div class="panel">
  <div class='btn-toolbar pull-right'>
  <?php $this->instance->printBackupBtn($instance);?>
  </div>
  <?php if(empty($backupList)):?>
    <div class="table-empty-tip">
      <p>
        <?php echo $lang->instance->errors->noBackup;?>
        <?php $this->instance->printBackupBtn($instance);?>
      </p>
    </div>
  <?php else:?>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th><?php echo $lang->instance->backup->date;?></th>
        <th><?php echo $lang->instance->backup->operator;?></th>
        <th><?php echo $lang->instance->backup->dbStatus;?></th>
        <th><?php echo $lang->instance->backup->dbSize;?></th>
        <th><?php echo $lang->instance->backup->restoreDate;?></th>
        <th><?php echo $lang->instance->backup->dbStatus;?></th>
        <th class="actions"><?php echo $lang->instance->backup->action;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($backupList as $backup):?>
    <tr>
      <td><span data-toggle='tooltip' data-placement='bottom' title='<?php echo date('Y-m-d H:i:s', $backup->create_time);?>'><?php echo date('Y-m-d', $backup->create_time);?></span></td>
      <td><?php echo $backup->username;?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? zget($lang->instance->backup->statusList, strtolower(zget($backup->backup_details->db[0], 'status'))) : '';?></td>
      <?php $backupSize = isset($backup->backup_details->db[0]) ? intval(zget($backup->backup_details->db[0], 'size', 0)) : 0;?>
      <?php $backupSize += isset($backup->backup_details->volume[0]) ? intval(zget($backup->backup_details->volume[0], 'doneBytes', 0)) : 0;?>
      <td><?php echo helper::formatKB($backupSize / 1024);?></td>
      <td><?php echo $backup->latest_restore_time ?  date('Y-m-d H:i:s', $backup->latest_restore_time) : '';?></td>
      <td><?php echo $backup->latest_restore_status ?  zget($lang->instance->restore->statusList, $backup->latest_restore_status, '') : '';?></td>
      <td><?php $this->instance->printRestoreBtn($instance, $backup);?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class="modal fade" id="confirmRestore" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog w-400px">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h2 class="text-center"><?php echo $lang->instance->backup->restore;?></h2>
          <div class="text-center confirm-text">
            <p class='modal-message text-center'><?php echo $lang->instance->backup->backupBeforeRestore;?></p>
            <p class='modal-message text-center'>
            <?php echo $lang->instance->backup->latestBackupAt;?>：
            <?php echo date('Y-m-d H:i:s', $latestBackup->create_time);?>
            </p>
          </div>
          <div class="text-center">
            <?php echo html::commonButton($lang->instance->backup->common, "instance-id='{$instance->id}'", 'btn-backup btn btn-wide');?>
            <?php echo html::commonButton($lang->instance->backup->restore, "id='submitRestore'", 'btn btn-primary btn-wide');?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
</div>
