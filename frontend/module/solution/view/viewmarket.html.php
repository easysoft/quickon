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
<div id='mainContent' class='main-row'>
  <div class='main-cell cell' id='solutionContainer'>
    <div class='info-header'>
      <div class='btn-toolbar pull-right'>
        <?php echo html::a($this->inlink('install', "id=$solution->id"), $lang->solution->install, "", "class='btn btn-primary btn-wide'");?>
      </div>
      <h2><?php echo $solution->title;?></h2>
    </div>
    <div class='row'>
      <div class='col-sm-9'>
        <div class='info-container'>
          <p><?php echo $solution->description;?></p>
          <?php if(isset($solution->arch_design_url)):?>
          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->diagram;?></h3></div>
            <p class='text-center'><?php echo html::image($solution->arch_design_url, "class='arch-design'");?></p>
          </div>
          <?php endif;?>

          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->scenes;?></h3></div>
            <?php foreach($solution->scenes as $scene):?>
            <div class='col-sm-3'>
              <h4 class='text-center'><?php echo $scene->name;?></h4>
              <p><?php echo $scene->description;?></p>
            </div>
            <?php endforeach;?>
          </div>

          <?php if(isset($components->categories)):?>
          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->includedApp;?></h3></div>
            <?php foreach($components->categories as $category => $groupedApp):?>
            <div class='col-sm-3'>
              <h4 class='text-gray'><?php echo $groupedApp->alias;?></h4>
              <?php foreach($groupedApp->choices as $cloudApp):?>
              <h5 class='app-list'>
                <?php $appInfo = zget($solution->apps, $cloudApp->name, '');?>
                <?php echo $appInfo ? html::image($appInfo->logo) : '';?>
                <?php echo $appInfo ? $appInfo->alias : '';?>
              </h5>
              <?php endforeach;?>
            </div>
            <?php endforeach;?>
          </div>
          <?php endif;?>

          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->features;?></h3></div>
            <?php foreach($solution->features as $feature):?>
            <div class='col-sm-3'>
              <h4 class='text-center'><?php echo $feature->name;?></h4>
              <p><?php echo $feature->description;?></p>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
      <div class='col-sm-3'>
        <h3><?php echo $lang->solution->relatedLinks;?></h3>
        <?php foreach($solution->links as $link):?>
        <div class='link-list'><?php echo html::a($link->url, $link->name, '_blank');?></div>
        <?php endforeach;?>
        <h3><?php echo $lang->solution->customers;?></h3>
        <?php foreach($solution->customers as $customer):?>
        <div class='customer-list'>
          <a href="<?php echo $customer->home;?>" target='_blank'>
            <?php echo html::image($customer->description);?>
            <h4 class='text-center'><?php echo $customer->name;?></h4>
          </a>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

