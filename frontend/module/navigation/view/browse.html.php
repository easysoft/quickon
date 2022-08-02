<?php
/**
 * The index view file of navigation module of QuCheng.
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
<div id="app">
  <nav class="sidenav">
    <a class="close-sidenav" href=""><i class="icon icon-close"></i></a>
    <h2><?php echo $this->lang->navigation->pinInstance;?></h2>
    <ul id="pinlist">
      <?php foreach($instances as $instance):?>
      <?php $active = ((bool)$instance->pinned) == true ? 'active' : '';?>
        <li><?php echo $instance->name;?><a class="instance-item <?php echo $active;?>" data-id="<?php echo $instance->id;?>" ><i class="icon icon-off"></i></a></li>
      <?php endforeach;?>
    </ul>
  </nav>
  <div class="content">
    <main>
      <div id="sortable">
      <?php foreach($pinnedInstances as $pinnedInstance):?>
        <section class="item-container" data-id="<?php echo $pinnedInstance->id;?>">
          <div class="item" style="background-color: #161b1f">
            <?php if($pinnedInstance->logo) echo html::image($pinnedInstance->logo, "class='instance-logo'");?>
            <div class="details">
              <div class="title white">
                <div class='title'><?php echo $pinnedInstance->name;?></div>
              </div>
            </div>
            <a title="<?php echo $pinnedInstance->introduction;?>" class="link white" target="_blank" href="<?php echo '//' . $pinnedInstance->domain;?>"><i class="fas fa-arrow-alt-to-right"></i></a>
          </div>
        </section>
      <?php endforeach;?>
        <section class="add-item">
          <a id="add-item" href=""><?php echo $this->lang->navigation->pinInstance;?></a>
        </section>
      </div>
      <div id="config-buttons">
        <a id="config-button" class="config" href=""><i class="icon icon-edit" style="font-size:20px;"></i></a>
      </div>
    </main>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
