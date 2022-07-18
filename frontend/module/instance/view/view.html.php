<?php
/**
 * The detail view file of instance module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     instance
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList',  array($instance->id));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left instance-name'>
    <h3><?php echo $instance->name;?></h3>
    <span><?php echo $instance->appVersion;?></span>
    <?php echo html::a($this->createLink('instance', 'editname', "id=$instance->id", '', true), '<i class="icon-edit"></i>', '', "class='iframe' title='$lang->edit' data-width='600' data-app='space'");?>
  </div>
  <div class='btn-toolbar pull-right instance-panel'>
    <div class="btn-group">
      <?php $this->instance->printActions($instance);?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <ul class="nav nav-tabs">
    <li class="<?php echo $tab == 'baseinfo' ? 'active' : '';?>"><a href="<?php echo $this->createLink('instance', 'view', "id=$instance->id&page=&perPage=&pageSize=&tab=baseinfo");?>">基本信息</a></li>
    <li class="<?php echo $tab == 'backup' ? 'active' : '';?>"><a href="<?php echo $this->createLink('instance', 'view', "id={$instance->id}&total=&perPage=&pageID=&tab=backup");?>">备份/还原</a></li>
  </ul>
  <div class="tab-content">
  <div class="tab-pane <?php echo $tab == 'baseinfo' ? 'active' : '';?>" id="baseInfo">
      <?php include 'baseinfo.html.php';?>
    </div>
    <div class="tab-pane <?php echo $tab == 'backup' ? 'active' : '';?>" id="backup">
      <?php include 'backup.html.php';?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
