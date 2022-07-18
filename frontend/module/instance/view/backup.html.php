<div class="panel">
  <div class='btn-toolbar pull-right'>
  <?php echo html::commonButton($lang->instance->backup->create, "instance-id='{$instance->id}'", "btn-backup btn btn-primary");?>
  </div>
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
    <?php foreach($backups as $backup):?>
    <tr>
      <td><?php echo $backup->backupAt;?></td>
      <td><?php echo $backup->backupAccount;?></td>
      <td><?php echo zget($lang->instance->backup->statusList, $backup->backupStatus);?></td>
      <td><?php echo zget($lang->instance->restore->statusList, $backup->restoreStatus);?></td>
      <td>
        <?php echo html::commonButton($lang->instance->backup->restore, "backup-id='{$backup->id}'", "btn-restore btn btn-link");?>
        <?php echo html::commonButton($lang->instance->backup->delete,  "backup-id='{$backup->id}'", "btn-delete-backup btn btn-link");?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
