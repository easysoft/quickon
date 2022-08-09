<?php
/**
 * The index view file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div class="row plug-container">
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink("backup", "index");?>'>
          <span class='logo'><i class="icon icon-info-sign"></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->systemInfo;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink("system", "dblist");?>'>
          <span class='logo'><img class="logo" src="/theme/default/images/main/db_logo.svg" /></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->dbManagement;?></span>
        </a>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
