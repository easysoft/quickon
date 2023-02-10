<?php
/**
 * The solution list of market view file of solution  module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-cell' id='solutionContainer'>
    <div>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('browseMarket'), "<span class='text'>{$lang->solution->market->browse}</span>", '', "class='btn-active-text'");?>
        <?php echo html::a(inlink('browse'), "<span class='text'>{$lang->solution->browse}</span>");?>
      </div>
    </div>
    <div class='row'>
      <?php foreach($solutionList->solutions as $solution):?>
      <div class='col-sm-3'>
        <div class="card">
          <a href='<?php echo helper::createLink('solution', 'viewMarket', "id=$solution->id");?>'>
            <?php echo html::image(isset($solution->background_url) ? $solution->background_url : "/theme/default/images/main/waterfall.png");?>
            <div class='card-heading'><span class='text-ellipsis' title='<?php echo $solution->title;?>'><?php echo $solution->title;?></span></div>
            <div class='card-content' title='<?php echo $solution->introduction;?>'><?php echo $solution->introduction;?></div>
          </a>
          <div class='card-actions'>
            <?php echo html::a($this->inlink('viewMarket', "id=$solution->id"), $lang->solution->detail, '', "class='label label-outline label-success'");?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
