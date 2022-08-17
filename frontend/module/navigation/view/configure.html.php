<?php
/**
 * The configure view file of navigation module of QuCheng.
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
  <div class="content">
    <main>
      <form class='form-ajax' method="post" target="hiddenwin">
        <section class="module-container">
          <header>
            <div class="section-title"><?php echo zget($this->lang->navigation->settings, $field);?></div>
            <div class="module-actions">
            </div>
          </header>
          <div class="create">
            <div class="input">
              <?php $checked = $value == 'on' ? 'checked' : '';?>
              <input type="hidden" name="value" value="0" />
              <label class="switch"><input type="checkbox" name="value" value="1" <?php echo $checked;?> /><span class="config-slider round"></span></label>
            </div>
          </div>
          <footer>
            <div class="section-title">&nbsp;</div>
            <div class="module-actions">
              <button type="submit" class="button"><i class="icon icon-save"></i><span><?php echo $lang->save;?></span></button>
              <?php echo html::a($this->createLink('navigation', 'settings'), "<span>{$lang->cancel}</span>", '', "class='button'");?>
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
