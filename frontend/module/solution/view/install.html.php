<?php
/**
 * The installation view file of solution module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solutionxxx
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include  $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('cloudSolutionID', $cloudSolution->id);?>
<div id='mainContent' class='main-row'>
  <div class='main-cell cell'>
    <div>
      <h2><?php echo $cloudSolution->title;?></h2>
    </div>
    <form id='installForm' method='post' class='form form-ajax not-watch'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th></th>
            <td><?php echo $lang->solution->chooseApp;?></td>
          </tr>
          <?php 
            foreach($components->category as $item):
          ?>
          <tr>
            <th><?php echo $item->alias;?></th>
            <td><?php echo html::select($item->name, $this->solution->createSelectOptions($item->choices, $cloudSolution), '', "class='form-control'");?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='text-center form-actions'>
        <?php echo html::commonButton($lang->solution->install, "id='submitBtn'", 'btn btn-primary btn-wide')?>
      </div>
    </form>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
