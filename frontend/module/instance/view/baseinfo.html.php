<div>
  <div class="panel">
    <div class="panel-heading">
      <div class="panel-title"><?php echo $lang->instance->serviceInfo;?></div>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col col-4">
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
        <div class='col col-8 usage-box'>
          <div class='col col-6'>
            <div class="c-title"><?php echo $lang->instance->cpuUsage;?></div>
            <?php $this->instance->printCpuUsage($instance, $instanceMetric->cpu, 'pie');?>
          </div>
          <div class='col col-6'>
            <div class="c-title"><?php echo $lang->instance->memUsage;?></div>
            <?php $this->instance->printMemUsage($instance, $instanceMetric->memory, 'pie');?>
          </div>
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
          <th class="w-180px"><?php echo $lang->instance->log->date;?></th>
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
