<?php
/**
 * The adminer view file of system module of QuCheng.
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
<div id='mainMenu' class='clearfix'>
</div>
<div id="mainContent" class="main-row">
  <iframe src="http://qucheng.minipc:4081/adminer" id="appIframe-adminer" name="app-adminer" allowfullscreen="true" frameborder="no" allowtransparency="true" scrolling="auto" style="width: 100%; height: 100%; left: 0px;">
  </iframe>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

