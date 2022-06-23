<?php
/**
 * The header view file of entry module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     entry
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('dev');?></div>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('entry', 'create'), "<i class='icon icon-plus'></i> {$lang->entry->create}", '', "class='btn btn-primary'"); ?>
  </div>
</div>
