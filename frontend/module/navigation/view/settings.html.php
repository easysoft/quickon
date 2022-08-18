<?php
/**
 * The settings view file of navigation module of QuCheng.
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
      <section class="module-container">
        <header>
          <div class="section-title"></div>
        </header>
        <table class="table table-hover">
          <thead>
            <tr>
              <th><?php echo $this->lang->navigation->label;?></th>
              <th style="width: 60%;"><?php echo $this->lang->navigation->value;?></th>
              <th class="text-center" style="width: 75px;"></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($settings as $setting):?>
            <tr>
              <td><?php echo zget($this->lang->navigation->settings, $setting->key);?></td>
              <td><?php echo zget($this->lang->navigation->settings, $setting->value);?></td>
              <td class="text-center"> <?php echo html::a($this->createLink('navigation', 'configure', 'field=' . $setting->key), "<i class='icon icon-edit'></i>");?></td>
            </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      </section>
      <div id="config-buttons">
      <?php echo html::a($this->createLink('navigation', 'browse'), '<i class="icon icon-home" style="font-size:20px;"></i>');?>
      <?php echo html::a($this->createLink('navigation', 'settings'), '<i class="icon icon-cog-outline" style="font-size:20px;"></i>');?>
      </div>
    </main>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
