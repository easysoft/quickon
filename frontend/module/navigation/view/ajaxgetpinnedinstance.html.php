<style>
#app {display: flex; min-height: 100vh; flex-direction: column; background-image: url("/background.jpg"); background-repeat: no-repeat; background-size: cover; background-position: bottom center;}
#app nav {display: flex; flex-direction: column; height: 100%; position: absolute; width: 340px; left: -340px; transition: all 0.35s ease-in-out; background: rgba(0, 0, 0, 0.7); color: white; z-index: 2;}
#app .content {flex-grow: 1; display: flex; flex-direction: column;}
#app main {flex-direction: column;}
#app main, #app #sortable {padding: 30px 10px; display: flex; justify-content: center; align-items: center; flex: 1; position: relative; flex-wrap: wrap; align-content: center; list-style: none; margin: 0;}
.item-container {position: relative;}
#app.sidebar nav {left: 0;}
.add-item {width: 280px; height: 90px; margin: 20px; flex: 0 0 280px; border-radius: 6px; padding: 20px; border: 4px dashed rgba(255, 255, 255, 0.7); box-shadow: 0 0 20px 2px rgba(0, 0, 0, 0.3); color: white; overflow: hidden; position: relative; display: none; outline: 1px solid transparent;}
.add-item.active {display: block;}
.add-item a {display: block; width: 100%; text-align: center; line-height: 40px; color: white; font-size: 19px;}
.item {width: 280px; height: 90px; margin: 20px; flex: 0 0 280px; background-image: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.25)); border-radius: 6px; padding: 15px; padding-right: 55px; color: white; overflow: hidden; position: relative; transition: all 0.35s ease-in-out; outline: 1px solid transparent; display: flex; align-items: center; border: 1px solid #333333; border: 1px solid #4a4a4a; border: 1px solid rgba(76, 76, 76, 0.4); -webkit-background-clip: padding-box; background-clip: padding-box;}
.item:after {content: ""; position: absolute; width: 90px; height: 90px; border-radius: 50%; position: absolute; right: -48px; top: 0px; background: rgba(255, 255, 255, 0.1); box-shadow: 0 0 40px 0px rgba(0, 0, 0, 0.2);}
.item .link {position: absolute; right: 0; top: 0; height: 100%; width: 100%; text-align: right; line-height: 90px; color: white; font-size: 24px; z-index: 1; padding-right: 10px;}
.item .title {font-size: 16px;}
.item .details {width: 100%;}
.instance-logo {width: 40px; display: block; height: 40px; margin-right: 15px;}
.sidenav {position: relative;}
.sidenav .close-sidenav {position: absolute; top: 20px; right: 20px; font-size: 24px; color: #ccc;}
.sidenav h2 {font-weight: 300; padding: 20px; margin: 0;}
.sidenav ul {list-style: none; margin: 0; padding: 20px;}
.sidenav ul li {display: flex; justify-content: space-between; padding: 5px;}
.sidenav ul li a {color: white;}
.sidenav ul li a.active {color: #46b0e6;}
hr {margin: 23px 0 18px; height: 0; border-style: none; border-width: 0; border-top: 1px solid #eaeaea; border-bottom: 1px solid #fff;}
.item-container .item-edit {color: white; position: absolute; bottom: 20px; left: 8px; width: 30px; height: 30px; background: rgba(0, 0, 0, 0.7); border-radius: 50%; text-align: center; line-height: 30px; display: none; z-index: 1;}
.black {color: #000 !important;}
.white {color: #fff !important;}
#app.header .item, #app.header .add-item {transform: scale(0.9); opacity: 0.8; margin: 20px 0;}
.app-icon-container {width: 60px; height: 60px; display: flex; justify-content: center; align-items: center; margin-right: 15px; flex: 0 0 60px;}
.icon-right-to-line {font-size: 25px;}
</style>
<div id="sortable">
  <?php if(!empty($pinnedInstances)):?>
  <?php foreach($pinnedInstances as $pinnedInstance):?>
  <section class="item-container" data-id="<?php echo $pinnedInstance->id;?>">
  <?php $isRunning = $pinnedInstance->status == 'running' ? true : false;?>
  <?php $itemStyle = $isRunning === true ? "background-color: #161b1f" : "background-color: grey";?>
    <div class="item" title='<?php echo zget($this->lang->instance->statusList, $pinnedInstance->status);?>' style="<?php echo $itemStyle;?>">
    <?php if($pinnedInstance->logo) echo html::image($pinnedInstance->logo, "class='instance-logo'");?>
      <div class="details">
        <div class="title white">
          <div class='title'><?php echo $pinnedInstance->name;?></div>
        </div>
      </div>
    <?php if($isRunning === true):?>
    <a title="<?php echo $pinnedInstance->introduction;?>" style="<?php $isRunning === true ? 'pointer-events: none;' : '' ?>" class="link white" target="_blank" href="<?php echo '//' . $pinnedInstance->domain;?>"><i class="icon icon-right-to-line"></i></a>
    <?php endif;?>
    </div>
  </section>
  <?php endforeach;?>
  <?php endif;?>
  <?php if(!empty($pinnedApps)):?>
  <?php foreach($pinnedApps as $app):?>
    <section class="item-container" data-id="<?php echo $app->id;?>">
      <div class="item" style="background-color: #161b1f;">
      <?php if($app->logo) echo html::image($app->logo, "class='instance-logo'");?>
        <div class="details">
          <div class="title white">
            <div class='title'><?php echo $app->title;?></div>
          </div>
        </div>
        <a title="<?php echo $app->desc;?>" style="pointer-events: none;" class="link white" target="_blank" href="<?php echo '//' . $pinnedInstance->domain;?>"><i class="icon icon-right-to-line"></i></a>
      </div>
    </section>
  <?php endforeach;?>
  <?php endif;?>
  <?php $isActive = (isset($showAddItem) and $showAddItem === true) ? 'active' : ''; ?>
  <section class="add-item <?php echo $isActive;?>">
    <a id="add-item" href=""><?php echo $this->lang->navigation->pinInstance;?></a>
  </section>
</div>
