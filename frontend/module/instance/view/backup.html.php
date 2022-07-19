<div class="panel">
  <div class='btn-toolbar pull-right'>
  <?php echo html::commonButton($lang->instance->backup->create, "instance-id='{$instance->id}'", "btn-backup btn btn-primary");?>
  </div>
  <?php if(empty($backupList)):?>
    <div class="table-empty-tip">
      <p><?php echo $lang->instance->errors->noBackup . html::commonButton($lang->instance->backup->create, "instance-id='{$instance->id}'", "btn-backup btn btn-primary");?></p>
    </div>
  <?php else:?>
  <table class="table">
    <thead>
      <tr>
        <th><?php echo $lang->instance->backup->date;?></th>
        <th><?php echo $lang->instance->backup->operator;?></th>
        <th><?php echo $lang->instance->backup->backupStatus;?></th>
        <th><?php echo $lang->instance->backup->restoreStatus;?></th>
        <th class="actions"><?php echo $lang->instance->backup->action;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($backupList as $backup):?>
    <tr>
      <td><?php echo date('Y-m-d H:i:s', $backup->create_time);?></td>
      <td><?php //echo $backup->username;?></td>
      <td><?php echo zget($lang->instance->backup->statusList, strtolower($backup->status));?></td>
      <td></td>
      <td>
        <?php echo html::commonButton($lang->instance->backup->restore, "instance-id='{$instance->id}' backup-name='{$backup->name}'", "btn-restore btn btn-link");?>
        <?php //echo html::commonButton($lang->instance->backup->delete,  "backup-id='{$backup->id}'", "btn-delete-backup btn btn-link");?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
