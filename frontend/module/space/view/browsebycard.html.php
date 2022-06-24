<?php
/**
 * The instance list view file of space module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<div class='main-cell' id='cardsContainer'>
  <?php if(empty($instances)):?>
  <div class="table-empty-tip">
    <p><?php echo html::a($this->createLink('store', 'browse'), $lang->space->noApps . ', ' . $lang->space->notice->toInstall, '', "class='btn btn-info'");?></p>
  </div>
  <?php else:?>
  <div class="row">
    <?php foreach ($instances as $instance):?>
    <div class='col-xs-6 col-sm-3 col-lg-2' data-id='<?php echo $instance->id;?>'>
      <div class='panel'>
        <a href="<?php echo helper::createLink('instance', 'view', "id=$instance->id");?>">
          <div class='panel-heading text-center'>
            <div class="instance-name"><?php echo $instance->name;?>&nbsp;</div>
          </div>
          <div class='panel-body'>
            <div class="instance-detail">
              <div class='instance-logo'>
                <?php echo html::image($instance->logo ? $instance->logo : '', "referrer='origin'");?>
              </div>
              <p class="instance-desc"><?php echo $instance->desc;?>&nbsp;</p>
            </div>
          </div>
          <div class='panel-footer instance-footer'>
            <div class="pull-left"><?php echo $instance->appVersion;?></div>
            <div class="pull-right instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
              <?php $this->instance->printStatus($instance);?>
            </div>
          </div>
        </a>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
<div>
