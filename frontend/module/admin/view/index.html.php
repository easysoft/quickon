<?php
/**
 * The view file of bug module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php
      $versionName = $config->version;
      printf($lang->admin->info->version, $versionName);
      ?>
    </h2>
  </div>
  <div>
    <table class='table table-form'>
      <tr>
        <td class='text-center w-160px'>
          <img class="about-logo" src='<?php echo $config->webRoot . 'theme/default/images/main/' . $lang->logoImg;?>' />
          <h4>
            <?php printf($lang->misc->qucheng->version, $config->version); ?>
          </h4>
        </td>
        <td>
          <p style="font-size:16px; text-align:center;"> <?php echo $lang->quchengSummary;?></p>
        </td>
      </tr>
      <tr>
        <td><div class='text-center'><?php echo $lang->designedByAIUX;?></div></td>
        <td></td>
      </tr>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
