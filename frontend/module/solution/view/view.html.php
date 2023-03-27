<?php
/**
 * The installed solution view file of solution module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include  $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('solutionID', $solution->id);?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('instanceIdList', array_keys($solution->instances));?>
<div id='mainContent' class='main-row'>
  <div class='cell' id='solutionContainer'>
    <div class='row'>
      <div class='col-sm-12'>
        <h3 class='solution-name' title='<?php echo $solution->name;?>'><?php echo $solution->name;?></h3>
        <?php echo html::a($this->createLink('solution', 'editname', "id=$solution->id", '', true), '<i class="icon-edit"></i>', '', "class='iframe edit-name-icon' title='$lang->edit' data-width='600'");?>
        <div class='pull-right'>
          <?php echo html::commonButton($lang->solution->uninstall, "id='uninstallBtn'", "btn btn-primary btn-wide");?>
        </div>
      </div>
      <div class='col-sm-9' id='instanceContainer'>
        <h3><?php echo $lang->solution->apps;?></h3>
        <?php
            $externalApps = array();
            foreach($solution->instances as $instance):
            if($instance->source == 'external')
            {
                  $externalApps[] = $instance;
                  continue;
            }
        ?>
        <div class='col-sm-4'>
          <div class='panel'>
            <div class='panel-heading'>
              <div class="instance-name">
                <a class='text-ellipsis' href="<?php echo helper::createLink('instance', 'view', "id=$instance->id");?>"  title='<?php echo $instance->name;?>'>
                  <?php echo $instance->name;?>
                </a>
              </div>
            </div>
            <div class='panel-body'>
              <div class="instance-detail">
                <a href="<?php echo helper::createLink('instance', 'view', "id=$instance->id");?>">
                  <div class='instance-logo'>
                    <?php echo html::image($instance->logo ? $instance->logo : '', "referrer='origin'");?>
                  </div>
                  <p class="instance-introduction" title='<?php echo $instance->introduction;?>'><?php echo $instance->introduction;?></p>
                </a>
              </div>
              <div class="instance-actions">
                <?php $canVisit = $this->instance->canDo('visit', $instance);?>
                <?php echo html::a($this->instance->url($instance), $lang->instance->visit, '_blank', "class='btn btn-primary' title='{$lang->instance->visit}'". ($canVisit ? '' : ' disabled style="pointer-events: none;"'));?>
              </div>
            </div>
            <div class='panel-footer instance-footer'>
              <?php $channel = zget($lang->instance->channelList, $instance->channel, '');?>
              <div class="pull-left"><?php echo $instance->appVersion . ($config->cloud->api->switchChannel && $channel ? " ($channel)" : '');?></div>
              <div class="pull-right instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
                <?php $this->instance->printStatus($instance);?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
        <div class='external clear'>
        <?php if(count($externalApps) > 0):?>
          <h3><?php echo $lang->solution->externalApps;?></h3>
          <?php foreach($externalApps as $instance){
              if($instance->source != 'external') continue;
          ?>
          <div class='col-sm-4'>
            <div class='panel'>
              <div class='panel-heading'>
                <div class="instance-name">
                  <a class='text-ellipsis' href="###"  title='<?php echo $instance->name;?>'>
                    <?php echo $instance->name;?>
                  </a>
                </div>
              </div>
              <div class='panel-body'>
                <div class="instance-detail">
                  <a href="###">
                    <div class='instance-logo'>
                      <?php echo html::image($instance->logo ? $instance->logo : '', "referrer='origin'");?>
                    </div>
                    <p class="instance-introduction" title='<?php echo $instance->introduction;?>'><?php echo $instance->introduction;?></p>
                  </a>
                </div>
                <div class="instance-actions">
                  <?php $canVisit = $this->instance->canDo('visit', $instance);?>
                  <?php echo html::a($this->instance->url($instance), $lang->instance->visit, '_blank', "class='btn btn-primary' title='{$lang->instance->visit}'". ($canVisit ? '' : ' disabled style="pointer-events: none;"'));?>
                </div>
              </div>
            </div>
          </div>
          <?php };?>
          <?php endif;?>
        </div>
      </div>
      <div class='col-sm-3'>
        <h3><?php echo $lang->solution->resources;?></h3>
        <div class='text-center'>
          <h4><?php echo $lang->instance->cpuUsage;?></h4>
          <div class='usage-pie'>
            <?php $this->solution->printCpuUsage($solution, 'pie');?>
          </div>
        </div>
        <div class='text-center'>
          <h4><?php echo $lang->instance->memUsage;?></h4>
          <div class='usage-pie'>
            <?php $this->solution->printMemUsage($solution, 'pie');?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
