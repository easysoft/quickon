<?php
/**
 * The browse view file of navigation module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJiang QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     navigation
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('backgroundImage', $this->session->backgroundImage);?>
<div id="app">
  <nav class="sidenav">
    <a class="close-sidenav" href=""><i class="icon icon-close"></i></a>
    <h2><?php echo $this->lang->navigation->pinInstance;?></h2>
    <ul id="pinlist">
      <?php foreach($instances as $instance):?>
      <?php $active = ((bool)$instance->pinned) == true ? 'active' : '';?>
        <li><?php echo $instance->name;?><a class="instance-item <?php echo $active;?>" data-id="<?php echo $instance->id;?>" ><i class="icon icon-pushpin"></i></a></li>
      <?php endforeach;?>
      <?php foreach($apps as $app):?>
      <?php $active = ((bool)$app->pinned) == true ? 'active' : '';?>
        <li><?php echo $app->title;?><a class="instance-item app-item <?php echo $active;?>" data-id="<?php echo $app->id;?>" ><i class="icon icon-pushpin"></i></a></li>
      <?php endforeach;?>
    </ul>
  </nav>
  <div class="content">
    <main>
      <div class='searchform'>
          <div id='search-container' class='input-container'>
            <input class="homesearch" autofocus="autofocus" oninput='searchInstance()' placeholder="<?php echo $this->lang->navigation->search;?>" name="instance" type="text">
            <button type='submit' class='searchbutton' onclick='searchInstance()'><?php echo $this->lang->navigation->search;?></button>
          </div>
      </div>
      <div id="sortable">
      <?php foreach($pinnedInstances as $pinnedInstance):?>
        <section class="item-container" data-id="<?php echo $pinnedInstance->id;?>">
        <?php $isRunning = $pinnedInstance->status == 'running' ? true : false;?>
        <?php $itemStyle = $isRunning === true ? "background-color: #161b1f" : "background-color: grey";?>
          <div class="item" title='<?php echo zget($this->lang->instance->statusList, $pinnedInstance->status);?>' style="<?php echo $itemStyle;?>">
          <?php if(!empty($pinnedInstance->logo)) echo html::image($pinnedInstance->logo, "class='instance-logo'");?>
            <div class="details">
              <div class="title white">
                <div class='title'><?php echo $pinnedInstance->name;?></div>
              </div>
            </div>
            <?php if($isRunning === true):?>
            <a title="<?php echo $pinnedInstance->introduction;?>" style="<?php $isRunning === true ? 'pointer-events: none;' : '' ?>" class="link white" target="_blank" href="<?php echo '//' . $pinnedInstance->domain;?>"><i class="icon icon-right-to-line"></i></a>
            <?php endif;?>
          </div>
          <?php echo html::a($this->createLink('navigation', 'edit', "id={$pinnedInstance->id}&type=instance"), '<i class="icon icon-pencil"></i>', '', "class='item-edit'");?>
        </section>
      <?php endforeach;?>
      <?php foreach($pinnedApps as $app):?>
        <section class="item-container" data-id="<?php echo $app->id;?>">
          <div class="item" style="background-color: #161b1f;">
          <?php if($app->logo) echo html::image($app->logo, "class='instance-logo'");?>
            <div class="details">
              <div class="title white">
                <div class='title'><?php echo $app->title;?></div>
              </div>
            </div>
            <a title="<?php echo $app->desc;?>"  class="link white" target="_blank" href="<?php echo $app->domain;?>"><i class="icon icon-right-to-line"></i></a>
          </div>
          <?php echo html::a($this->createLink('navigation', 'edit', "id={$app->id}&type=app"), '<i class="icon icon-pencil"></i>', '', "class='item-edit'");?>
        </section>
      <?php endforeach;?>
        <section class="add-item">
          <a id="add-item" href=""><?php echo $this->lang->navigation->pinInstance;?></a>
        </section>
      </div>
      <div class='power'><?php echo $this->lang->navigation->power;?></div>
      <div id="config-buttons">
        <?php if($user !== 'anonymous'):?>
        <a id="config-button" class="config" href=""><i class="icon icon-edit" style="font-size:20px;"></i></a>
        <?php endif;?>
        <?php echo html::a($this->createLink('navigation', 'create'), '<i class="icon icon-plus" style="font-size:20px;"></i>');?>
        <?php echo html::a($this->createLink('navigation', 'settings'), '<i class="icon icon-cog-outline" style="font-size:20px;"></i>');?>
      </div>
    </main>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
