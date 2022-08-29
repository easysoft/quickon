<?php
/**
 * The create view file of navigation module of QuCheng.
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
  <div class="content">
    <main>
      <form class="form-ajax" method="post" target='hiddenwin' id="itemform">
        <section class="module-container">
          <header>
            <div class="section-title"><?php echo $this->lang->navigation->addApp;?></div>
          </header>
          <div id="create" class="create">
            <div class="input">
              <label><?php echo $this->lang->navigation->appName;?></label>
              <?php echo html::input('title', '', "class='form-control' id='appName'");?>
            </div>
            <div class="input">
              <label><?php echo $this->lang->navigation->url;?></label>
              <?php echo html::input('domain', '', "class='form-control' id='appurl'");?>
            </div>
            <div id="sapconfig"></div>
          </div>
          <footer>
            <div class="section-title"></div>
            <div class="module-actions">
              <button type="submit" class="button"><i class="icon icon-save"></i><span><?php echo $lang->save;?></span></button>
              <?php echo html::a($this->createLink('navigation', 'browse'), "<span>{$lang->cancel}</span>", '', "class='button'");?>
            </div>
          </footer>
        </section>
      </form>
      <div id="config-buttons">
      <?php echo html::a($this->createLink('navigation', 'browse'), '<i class="icon icon-home" style="font-size:20px;"></i>');?>
      <?php echo html::a($this->createLink('navigation', 'settings'), '<i class="icon icon-cog-outline" style="font-size:20px;"></i>');?>
      </div>
    </main>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
