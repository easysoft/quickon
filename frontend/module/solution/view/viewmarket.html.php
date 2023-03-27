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
  <div class='row'>
    <div class='col-sm-12'>
      <h2 class='solution-name'><?php echo $solution->title;?></h2>
      <div class='pull-right'>
        <?php echo html::a($this->inlink('install', "id=$solution->id"), $lang->solution->install, "", "class='install-btn btn btn-primary btn-wide'");?>
      </div>
    </div>
  </div>
  <div class='main-cell' id='solutionContainer'>
    <div class='row'>
      <div class='col-sm-9'>
        <div class='cell info-container'>
          <h3 class=''><?php echo $lang->solution->introduction;?></h3>
          <div><?php echo $solution->description;?></div>
          <?php if(isset($solution->arch_design_url)):?>
          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->diagram;?></h3></div>
            <div class='text-center'><?php echo html::image($solution->arch_design_url, "class='arch-design'");?></div>
          </div>
          <?php endif;?>

          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->scenes;?></h3></div>
            <?php foreach($solution->scenes as $scene):?>
            <div class='col-sm-4'>
              <div class='cell text-center'>
                <h4><?php echo $scene->name;?></h4>
                <p><?php echo $scene->description;?></p>
              </div>
            </div>
            <?php endforeach;?>
          </div>

          <?php if(isset($components->category)):?>
          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->includedApp;?></h3></div>
            <?php foreach($components->category as $category):?>
            <div class='col-sm-12'>
              <div class='app-box'>
                <div class='component-title text-left'><span class=''><?php echo $category->alias;?></span></div>
                <div>
                  <?php foreach($category->choices as $cloudApp):?>
                  <div class='app-list'>
                    <?php $appInfo = zget($solution->apps, $cloudApp->name, '');?>
                    <?php echo $appInfo ? html::image($appInfo->logo) : '';?>
                    <?php echo $appInfo ? $appInfo->alias : '';?>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
            <?php endforeach;?>
          </div>
          <?php endif;?>

          <div class='row'>
            <div class='col-sm-12'><h3><?php echo $lang->solution->features;?></h3></div>
            <?php foreach($solution->features as $feature):?>
            <div class='col-sm-6'>
              <div class='cell feature-box'>
                <img src='/theme/default/images/main/medal.svg'/>
                <h4 class=''><?php echo $feature->name;?></h4>
                <p><?php echo $feature->description;?></p>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
      <div class='col-sm-3 right-side'>
        <div class='cell'>
          <h3><?php echo $lang->solution->relatedLinks;?></h3>
          <?php foreach($solution->links as $link):?>
          <div class='link-list'><?php echo html::a($link->url, $link->name . "<span>&nbsp;&rarr;</span>", '_blank');?></div>
          <?php endforeach;?>
        </div>
        <div class='cell'>
          <h3><?php echo $lang->solution->customers;?></h3>
          <?php foreach($solution->customers as $customer):?>
          <div class='customer-list'>
            <a href="<?php echo $customer->home;?>" target='_blank'>
              <?php echo html::image($customer->description);?>
              <h4 class='text-center' title='<?php echo $customer->name;?>'><?php echo $customer->name;?></h4>
            </a>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
