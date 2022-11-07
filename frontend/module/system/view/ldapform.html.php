<form id='LDAPForm' class='cell load-indicator main-form form-ajax'>
  <h4>
    <?php $enableLDAP = $this->system->hasSystemLDAP();?>
    <?php echo html::checkbox('enableLDAP', array('true' => $lang->system->ldapEnabled), $enableLDAP ? 'true' : '', (($ldapLinked or $activeLDAP) ? "onclick='return false;'" : '')); ?>
  </h4>
  <table class='table table-form'>
    <tbody>
      <tr>
        <th><?php echo $lang->system->ldapSource;?></th>
        <td class='required w-300px'><?php echo html::select('source', $lang->system->ldapTypeList, $activeLDAP,  ($ldapLinked ? 'disabled' : '') . " class='form-control'");?></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <table id='quchengLDAP' class='table table-form'>
    <tbody>
      <tr>
        <th><?php echo $lang->system->ldapUsername?></th>
        <td><?php echo $ldapApp->account->username;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->ldapRoot;?></th>
        <td><?php echo 'dc=quickon,dc=org';?></td>
      <tr>
    </tbody>
  </table>
  <div id='extraLDAP'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->host;?></th>
          <td class='required w-300px'><?php echo html::input('extra[host]', zget($ldapSettings, 'host', ''), "class='form-control required' placeholder='192.168.1.1'");?></td>
            <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->port;?></th>
          <td class='required'><?php echo html::input('extra[port]',  zget($ldapSettings, 'port', ''), "class='form-control' placeholder='389'");?></td>
            <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->ldapUsername;?></th>
          <td class='required'><?php echo html::input('extra[bindDN]', zget($ldapSettings, 'bindDN', ''), "class='form-control' placeholder='admin'");?></td>
            <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->password;?></th>
          <td class='required'><?php echo html::input('extra[bindPass]', zget($ldapSettings, 'bindPass', ''), "class='form-control' placeholder='******'");?></td>
            <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->ldapRoot;?></th>
          <td class='required'><?php echo html::input('extra[baseDN]', zget($ldapSettings, 'baseDN', ''), "class='form-control' placeholder='dc=quickon,dc=org'");?></td>
            <td></td>
        </tr>
      </tbody>
    </table>
    <div class='advanced'><?php echo html::a("#advanced-settings", $lang->system->ldapAdvance . "<i class='icon icon-chevron-double-down'></i>", '', "data-toggle='collapse'");?></div>
    <table class="collapse table table-form" id="advanced-settings">
      <tbody>
          <tr>
            <th><?php echo $lang->system->filterUser;?></th>
            <td class='w-300px'><?php echo html::input('extra[filter]', zget($ldapSettings, 'filter', '&(objectClass=posixAccount)(uid=%s)'), "class='form-control' placeholder='&(objectClass=posixAccount)(cn=%s)'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->email;?></th>
            <td class='w-300px'><?php echo html::input('extra[attrEmail]', zget($ldapSettings, 'attrEmail', 'mail'), "class='form-control' placeholder='mail'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->extraAccount;?></th>
            <td class='w-300px'><?php echo html::input('extra[attrUser]', zget($ldapSettings, 'attrUser', 'uid'), "class='form-control' placeholder='uid'");?></td>
            <td></td>
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
  <div class='text-center form-actions'><?php echo html::submitButton($activeLDAP ? $lang->system->ldapUpdate : $lang->system->ldapInstall);?></div>
</form>