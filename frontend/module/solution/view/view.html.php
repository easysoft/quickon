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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <h3><?php echo $solution->name;?></h3>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-cell cell' id='solutionContainer'>
    <div>
      <div class="panel">
        <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->solution->common;?></div>
        </div>
        <div class="panel-body">
        </div>
      </div>
    </div>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

