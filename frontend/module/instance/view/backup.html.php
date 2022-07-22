<div class="panel">
  <div class='btn-toolbar pull-right'>
  <?php $this->instance->printBackupBtn($instance);?>
  </div>
  <?php if(empty($backupList)):?>
    <div class="table-empty-tip">
      <p><?php echo $lang->instance->errors->noBackup; $this->instance->printBackupBtn($instance);?></p>
    </div>
  <?php else:?>
  <table class="table">
    <thead>
      <tr>
        <th><?php echo $lang->instance->backup->date;?></th>
        <th><?php echo $lang->instance->backup->operator;?></th>
        <th><?php echo $lang->instance->backup->backupStatus;?></th>
        <th class="actions"><?php echo $lang->instance->backup->action;?></th>
        <th colspan="3"><?php echo $lang->instance->backup->restoreInfo;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($backupList as $backup):?>
    <tr>
      <td rowspan="<?php echo max(count($backup->restores), 1);?>"><?php echo date('Y-m-d H:i:s', $backup->create_time);?></td>
      <td rowspan="<?php echo max(count($backup->restores), 1);?>"><?php echo $backup->creator;?></td>
      <td rowspan="<?php echo max(count($backup->restores), 1);?>"><?php echo zget($lang->instance->backup->statusList, strtolower($backup->status));?></td>
      <td rowspan="<?php echo max(count($backup->restores), 1);?>">
        <?php $this->instance->printRestoreBtn($instance, $backup);?>
      </td>
      <?php if(count($backup->restores)):?>
      <?php $restore = array_shift($backup->restores);?>
      <td><?php echo date('Y-m-d H:i:s', $restore->create_time);?>
      <td><?php echo $restore->creator;?></td>
      <td><?php echo zget($lang->instance->restore->statusList, strtolower($backup->status));?></td>
      <?php else:?>
      <td colspan="3"></td>
      <?php endif;?>
    </tr>
    <?php if(count($backup->restores)):?>
    <?php foreach($backup->restores as $restore):?>
    <tr>
      <td><?php echo date('Y-m-d H:i:s', $restore->create_time);?>
      <td><?php echo $restore->creator;?></td>
      <td><?php echo zget($lang->instance->restore->statusList, strtolower($backup->status));?></td>
    </tr>
    <?php endforeach;?>
    <?php endif;?>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
