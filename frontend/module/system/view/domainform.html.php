<form id='smtpForm' class='cell load-indicator main-form form-ajax'>
  <table class="table table-form">
    <tbody>
      <tr>
        <th><?php echo $lang->system->domain->oldDomain;?></th>
        <td colspan='2' ><?php echo $config->CNE->app->domain;?> <span class='with-padding text-danger'><?php echo $lang->system->domain->notReuseOldDomain;?></span></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->domain->newDomain;?></th>
        <td class='required w-400px'>
          <?php echo html::input('customDomain', zget($domainSettings, 'customDomain', ''), "class='form-control' placeholder=''");?>
          <div class='with-padding'>
            <span><?php echo $lang->system->domain->setDNS;?></span>
            <?php echo html::a('#', $lang->system->domain->dnsHelperLink, '', "class='text-primary'");?>
          </div>
        </td>
        <td></td>
        <td></td>
      </tr>
      <tr class='hide'>
        <td></td>
        <td colspan='2'>
          <h4>
          <?php $domainUsed = false;?>
          <?php echo html::checkbox('https', array('true' => $lang->system->domain->uploadCert), $domainSettings->https, ($domainUsed ? "onclick='return false;'" : ''));?>
          </h4>
        </td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <table class="hide table table-form">
    <tbody>
      <tr>
        <th><?php echo $lang->system->domain->publicKey;?></th>
        <td class='required' colspan='2'><?php echo html::textarea('publicKey', zget($domainSettings, 'publicKey', ''), "class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->system->domain->privateKey;?></th>
        <td class='required' colspan='2'><?php echo html::textarea('privateKey', zget($domainSettings, 'privateKey', ''), "class='form-control'");?></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <div class='text-center form-actions'><?php echo html::submitButton($lang->save);?></div>
</form>
