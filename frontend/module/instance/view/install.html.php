<?php
/**
 * The custom install view file of instance module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.cn)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.cn
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->instance->installApp . $cloudApp->alias . $cloudApp->app_version;?></h2>
    </div>
    <form id='installForm' action='<?php echo helper::createLink("instance", "install", "id={$cloudApp->id}", '', '', false);?>' class="cell not-watch load-indicator main-form">
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-70px'><?php echo $lang->instance->name;?></th>
            <td class='w-230px'>
              <div class='input-group'>
                <?php echo html::input('customName', $cloudApp->alias, "class='form-control' maxlength='20'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->instance->domain;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('customDomain', $thirdDomain, "class='form-control' maxlength='20'");?>
                <span class='input-group-addon'><?php echo $config->CNE->app->domain;?></span>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->instance->dbType;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::radio('dbType', $lang->instance->dbTypes, 'sharedDB');?>
              </div>
            </td>
            <td><?php echo html::a('https://www.qucheng.com/book/Installation-manual/app-market-17.html',$lang->instance->howToSelectDB, '_blank');?></td>
          </tr>
          <tr>
            <th></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('dbService', $dbList, '', "class='form-control'");?>
              </div>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div class="text-center form-actions"><?php echo html::submitButton($lang->instance->install);?></div>
    </form>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
