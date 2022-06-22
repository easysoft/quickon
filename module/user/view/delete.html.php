<?php
/**
 * The delete view file of user module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     user
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo sprintf($lang->user->noticeDelete, $user->realname);?></h2>
  </div>
  <form method='post' id='dataform' target='hiddenwin' style='padding: 20px 5% 40px'>
    <table class='w-p100 table-form'>
      <tr>
        <th class='w-120px text-right'>
          <?php echo $lang->user->verifyPassword;?>
        </th>
        <td>
          <div class="required required-wrapper"></div>
          <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
        </td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton($lang->delete);?></td>
      </tr>
    </table>
  </form>
</div>
<?php echo html::hidden('verifyRand', $rand);?>
<?php include '../../common/view/footer.lite.html.php';?>
