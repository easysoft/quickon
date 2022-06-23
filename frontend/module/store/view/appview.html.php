<?php
/**
 * The app view file of store module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   store
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('cloudApp', $cloudApp);?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('store', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div class="cell">
      <div class="main-header">
        <h2 class="app-name"><?php echo $cloudApp->alias;?></h2>
        <div class="btn-group dropdown pull-right">
          <?php echo html::commonButton($lang->store->install, "app-id='{$cloudApp->id}'", 'btn-install btn btn-primary');?>
          <?php if(!empty($components)):?>
          <button class="btn btn-primary" data-toggle="dropdown"><span class="caret"></span></button>
          <ul class="dropdown-menu pull-right" id='exportActionMenu'>
            <li><?php common::printLink('instance', 'custominstall', "id={$cloudApp->id}", $lang->store->customInstall,'',);?></li>
          </ul>
          <?php endif?>
        </div>
      </div>
      <table class="table table-data">
        <tbody>
          <tr>
            <td class="w-120px app-logo"><div style="text-align: right;"><?php echo html::image($cloudApp->logo, "referrer='origin'");?></div></td>
            <td class="app-desc"><?php echo $cloudApp->desc;?></td>
            <td class="w-120px app-action"></td>
          </tr>
          <tr>
            <th><?php echo $lang->store->appVersion;?></th>
            <td><?php echo $cloudApp->app_version;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->store->releaseDate;?></th>
            <td><?php echo (new DateTime($cloudApp->publish_time))->format('Y-m-d');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->store->author;?></th>
            <td><?php echo $cloudApp->author;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->store->appType;?></th>
            <td><?php echo trim(implode('/', array_column($cloudApp->categories, 'alias')), '/');?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
