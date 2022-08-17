<div class="panel">
  <div class="panel-heading">
    <div class="panel-title"><?php echo $lang->instance->domain;?>:<span><?php echo $instance->domain;?></span></div>
  </div>
  <?php if(!empty($dbList)):?>
  <hr/>
  <div class="panel-heading">
    <div class="panel-title"><?php echo $lang->instance->dbList;?></div>
  </div>
  <div class="panel-body">
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th><?php echo $lang->instance->dbName?></th>
          <th><?php echo $lang->instance->dbType;?></th>
          <th><?php echo $lang->instance->dbStatus;?></th>
          <th><?php echo $lang->instance->action?></th>
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

