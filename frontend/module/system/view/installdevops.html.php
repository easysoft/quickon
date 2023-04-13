<?php
/**
 * The installation view file of solution module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Zeng<zenggang@easycorp.ltd>
 * @package     solutionxxx
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include  $this->app->getModuleRoot() . '/common/view/header.lite.html.php';?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('cloudSolutionID', $cloudSolution->id);?>
<div class='container'>
<div class='modal-dialog'>
<div class='modal-body'>
    <div>
      <h2><?php echo $lang->system->devops->peizhi;?></h2>
    </div>
    <form id='installForm' method='post' class='form not-watch'>
      <table class='table table-form'>
        <tbody>
          <?php
            foreach($components->category as $item):
                if($step == 1 and $item->name != 'pms') continue;
                if($step == 2 and $item->name != 'git') continue;
          ?>
          <tr>
            <th class='w-130px'><?php echo $lang->system->devops->selectPms;?></th>
            <td><?php echo html::radio($item->name, $this->solution->createSelectOptions($item->choices, $cloudSolution), $item->choices[0]->name);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='text-center form-actions'>
        <?php if($step == 2):?>
        <?php echo html::a($this->createLink('system', 'installDevops'), $lang->solution->prevStep, '', 'class="btn btn-primary btn-wide"');?>
        <?php echo html::submitButton($lang->solution->install, "id='submitBtn'", 'btn btn-primary btn-wide')?>
        <?php else:?>
        <?php echo html::submitButton($lang->solution->nextStep, "", 'btn btn-primary btn-wide')?>
        <?php endif;?>
      </div>
    </form>
</div>
</div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.lite.html.php';?>
