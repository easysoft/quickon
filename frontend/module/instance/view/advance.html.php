<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->mem;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='LDAPForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <th></th>
          <td><?php $this->instance->printSuggestedMemory($instanceMetric->memory, $lang->instance->memOptions);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->instance->adjustMem;?></th>
          <td class='w-100px'><?php echo html::select('memory_kb', $lang->instance->memOptions, '', "class='form-control'");?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='memBtn' instance-id='$instance->id'", 'btn btn-primary'); ?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php if(!empty($dbList)):?>
  <hr/>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->dbList;?></div>
  </div>
  <div class='panel-body'>
    <table class='table table-bordered text-center'>
      <thead>
        <tr>
          <th><?php echo $lang->instance->dbName;?></th>
          <th><?php echo $lang->instance->dbType;?></th>
          <th><?php echo $lang->instance->dbStatus;?></th>
          <th><?php echo $lang->instance->action;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($dbList as $db):?>
        <tr>
          <td><?php echo $db->db_name;?></td>
          <td><?php echo $db->db_type?></td>
          <td><?php echo $db->ready ? $lang->instance->dbReady : $lang->instance->dbWaiting;?></td>
          <td><?php $this->instance->printDBAction($db, $instance);?></td>
        <tr>
        <?php endforeach;?>
      <tbody>
    </table>
  </div>
  <?php endif;?>
</div>

