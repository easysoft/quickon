<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->mem;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='memoryForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <th></th>
          <td colspan="3">
            <span class='label label-info'><?php echo $lang->instance->currentMemory;?>：<?php echo helper::formatKB($currentResource->resources->memory / 1024);?></span>
            <span class='label label-warning'><?php $this->instance->printSuggestedMemory($instanceMetric->memory, $lang->instance->memOptions);?></span>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->instance->adjustMem;?></th>
          <td class='w-100px'><?php echo html::select('memory_kb', $this->instance->filterMemOptions($currentResource), '', "class='form-control'");?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php $disableMemBtn = $instance->status == 'running' ? '' : 'disabled';?>
            <?php echo html::commonButton($lang->instance->saveSetting, "{$disableMemBtn} id='memBtn' instance-id='$instance->id'", 'btn btn-primary'); ?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php if(isset($cloudApp->features->ldap)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->system->ldapManagement;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='LDAPForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <tr>
          <?php $LDAPInstalled =  $this->system->hasSystemLDAP();?>
          <?php $enableLDAP    =  $instance->ldapSnippetName ? 'true' : '' ;?>
          <td class='w-100px'><?php echo html::checkbox('enableLDAP', array('true' => $lang->instance->enableLDAP),  $enableLDAP, ($LDAPInstalled ? '' : 'disabled'));?></td>
          <td colspan='2'>
            <?php if(!$LDAPInstalled):?>
            <?php echo $lang->instance->systemLDAPInactive;?>
            <?php echo html::a(helper::createLink('system', 'installLDAP'), $lang->instance->toSystemLDAP, '', "class='btn btn-link'");?>
            <?php endif?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='w-100px text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='ldapBtn' instance-id='$instance->id'" . ($LDAPInstalled ? '' : 'disabled'), 'btn btn-primary'); ?>
          </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>
  <?php if(count($customItems)):?>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->instance->customSetting;?></span></div>
  </div>
  <div class='panel-body'>
    <form id='customForm' class='cell not-watch load-indicator'>
      <table class='table table-form'>
        <?php foreach($customItems as $item):?>
        <tr>
          <th><?php echo $item->label;?></th>
          <td>
            <?php echo html::input($item->name, $item->default, "class='form-control'");?>
          </td>
          <td></td>
          <td></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php echo html::commonButton($lang->instance->saveSetting, "id='customBtn' instance-id='$instance->id'", 'btn btn-primary'); ?>
           </td>
          <td></td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
  <?php endif;?>
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
          <td><?php echo $db->db_type;?></td>
          <td><?php echo $db->ready ? $lang->instance->dbReady : $lang->instance->dbWaiting;?></td>
          <td><?php $this->instance->printDBAction($db, $instance);?></td>
        <tr>
        <?php endforeach;?>
      <tbody>
    </table>
  </div>
  <?php endif;?>
</div>
