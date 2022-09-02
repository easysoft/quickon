<div class="panel">
  <div class='btn-toolbar pull-right'>
  <?php $this->instance->printBackupBtn($instance);?>
  </div>
  <?php if(empty($backupList)):?>
    <div class="table-empty-tip">
      <p><?php echo $lang->instance->errors->noBackup; $this->instance->printBackupBtn($instance);?></p>
    </div>
  <?php else:?>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th rowspan="2"><?php echo $lang->instance->backup->date;?></th>
        <th rowspan="2"><?php echo $lang->instance->backup->operator;?></th>
        <th colspan="5"><?php echo $lang->instance->backup->database;?></th>
        <th colspan="5"><?php echo $lang->instance->backup->volumne;?></th>
        <th colspan="2"><?php echo $lang->instance->backup->lastRestore;?></th>
        <th rowspan="2" class="actions"><?php echo $lang->instance->backup->action;?></th>
      </tr>
      <tr>
        <th><?php echo $lang->instance->backup->dbType;?></th>
        <th><?php echo $lang->instance->backup->dbName;?></th>
        <th><?php echo $lang->instance->backup->dbStatus;?></th>
        <th><?php echo $lang->instance->backup->dbSpentSeconds;?></th>
        <th><?php echo $lang->instance->backup->dbSize;?></th>
        <th><?php echo $lang->instance->backup->volName;?></th>
        <th><?php echo $lang->instance->backup->volMountName;?></th>
        <th><?php echo $lang->instance->backup->volStatus;?></th>
        <th><?php echo $lang->instance->backup->volSpentSeconds;?></th>
        <th><?php echo $lang->instance->backup->volSize;?></th>
        <th><?php echo $lang->instance->backup->restoreDate;?></th>
        <th><?php echo $lang->instance->backup->dbStatus;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($backupList as $backup):?>
    <tr>
      <td><?php echo date('Y-m-d H:i:s', $backup->create_time);?></td>
      <td><?php echo $backup->username;?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? zget($backup->backup_details->db[0], 'db_type') : '';?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? zget($backup->backup_details->db[0], 'db_name') : '';?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? zget($lang->instance->backup->statusList, strtolower(zget($backup->backup_details->db[0], 'status'))) : '';?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? zget($backup->backup_details->db[0], 'cost') : '';?></td>
      <td><?php echo isset($backup->backup_details->db[0]) ? helper::formatKB(zget($backup->backup_details->db[0], 'size') / 1024) : '';?></td>
      <td><?php echo isset($backup->backup_details->volume[0]) ? zget($backup->backup_details->volume[0], 'pvc_name') : '';?></td>
      <td><?php echo isset($backup->backup_details->volume[0]) ? zget($backup->backup_details->volume[0], 'volume') : '';?></td>
      <td><?php echo isset($backup->backup_details->volume[0]) ? zget($lang->instance->backup->statusList, strtolower(zget($backup->backup_details->volume[0], 'status'))) : '';?></td>
      <td><?php echo isset($backup->backup_details->volume[0]) ? zget($backup->backup_details->volume[0], 'cost') : '';?></td>
      <td><?php echo isset($backup->backup_details->volume[0]) ? helper::formatKB(zget($backup->backup_details->volume[0], 'doneBytes') / 1024) : '';?></td>
      <td><?php echo $backup->latest_restore_time ?  date('Y-m-d H:i:s', $backup->latest_restore_time) : '';?></td>
      <td><?php echo $backup->latest_restore_status ?  zget($lang->instance->backup->statusList, $backup->latest_restore_status, '') : '';?></td>
      <td><?php $this->instance->printRestoreBtn($instance, $backup);?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
