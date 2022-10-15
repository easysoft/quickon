<?php
/**
 * The edit LDAP view file of system module of chandao.net.
 *
 * @copyright Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.chandao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('errors', $lang->system->errors);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->ldapManagement;?></h2>
    </div>
    <form id='installLDAP' class='cell load-indicator main-form form-ajax'>
      <div id='extraLDAP'>
        <table class='table table-form'>
          <tbody>
            <tr>
              <th><?php echo $lang->system->host;?></th>
              <td><?php echo html::input('extra[host]', zget($ldapSettings, 'host', ''), "class='form-control' placeholder='192.168.1.1'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->system->port;?></th>
              <td><?php echo html::input('extra[port]',  zget($ldapSettings, 'port', ''), "class='form-control' placeholder='389'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->system->ldapUsername;?></th>
              <td><?php echo html::input('extra[bindDN]', zget($ldapSettings, 'bindDN', ''), "class='form-control' placeholder='admin'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->system->password;?></th>
              <td><?php echo html::input('extra[bindPass]', zget($ldapSettings, 'bindPass', ''), "class='form-control' placeholder='******'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->system->ldapRoot;?></th>
              <td><?php echo html::input('extra[baseDN]', zget($ldapSettings, 'baseDN', ''), "class='form-control' placeholder='dc=quickon,dc=org'");?></td>
            </tr>
          </tbody>
        </table>
        <div class='advanced'><?php echo html::a("#advanced-settings", $lang->system->ldapAdvance . "<i class='icon icon-chevron-double-down'></i>", '', "data-toggle='collapse'");?></div>
        <table class="collapse table table-form" id="advanced-settings">
          <tbody>
              <tr>
                <th><?php echo $lang->system->filterUser;?></th>
                <td><?php echo html::input('extra[filter]', zget($ldapSettings, 'filter', ''), "class='form-control' placeholder='&(objectClass=posixAccount)(cn=%s)'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->system->email;?></th>
                <td><?php echo html::input('extra[attrEmail]', zget($ldapSettings, 'attrEmail', ''), "class='form-control' placeholder='mail'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->system->extraAccount;?></th>
                <td><?php echo html::input('extra[attrUser]', zget($ldapSettings, 'attrUser', ''), "class='form-control' placeholder='uid'");?></td>
              </tr>
          </tbody>
        </table>
        <table class='table table-form'>
          <tbody>
            <tr>
              <td class='w-100px text-right'><?php echo html::commonButton($lang->system->connectLdap, "id='testConnectBtn'");?></td>
              <td class='w-300px text-left'><span id='connectResult'></span></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class='text-center form-actions'><?php echo html::submitButton($lang->system->ldapUpdate);?></div>
    </form>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

