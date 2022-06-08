<?php
/**
 * The html file of init method of admin module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
?>
<?php
include $this->app->getModuleRoot() . '/common/view/header.lite.html.php';
if(empty($config->notMd5Pwd))js::import($jsRoot . 'md5.js');
?>
<main id="main" class="fade no-padding">
  <div class='container'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->admin->initAdmin;?></strong>
      </div>
      <div class='modal-body'>
        <form method='post' target='hiddenwin'>
          <table class='table table-form mw-400px' style='margin: 0 auto'>
            <tr>
              <th><?php echo $lang->admin->account;?></th>
              <td><?php echo html::input('account', '', "class='form-control' maxlength='20'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->admin->password;?></th>
              <td><?php echo html::password('password', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->admin->password2;?></th>
              <td><?php echo html::password('password2', '', "class='form-control'");?></td>
            </tr>
            <tr class='text-center'>
              <td colspan='2'><?php echo html::submitButton($lang->admin->submit);?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</main>
<?php include $this->app->getModuleRoot() . '/common/view/footer.lite.html.php';?>
