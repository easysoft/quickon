<?php include $this->app->getModuleRoot() . '/common/view/header.lite.html.php';?>
<?php
css::import($jsRoot . 'xterm/css/xterm.css');
js::import($jsRoot . 'xterm/lib/xterm.js');
?>
<?php js::set('solutionID', $solution->id);?>
<?php js::set('hasError', !in_array($solution->status, array('installing', 'installed', 'finish')));?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('installLabel', $lang->solution->install);?>
<?php js::set('configLabel', $lang->solution->config);?>
<div class='container'>
<div class='modal-dialog'>
<div class='modal-body'>
    <div class='solution-progress'>
      <h2><?php echo $lang->system->devops->peizhi;?></h2>
      <div class='text-center'>
        <?php $components = json_decode($solution->components);?>
        <?php $order =0;?>
        <?php foreach($components as $category => $cloudApp):?>
        <?php $active = (isset($cloudApp->status) && $cloudApp->status !='waiting') ? 'active' : '';?>
        <?php if($order++ > 0):?>
            <div class='arrow app-<?php echo $cloudApp->id;?> <?php echo $active;?>'>&rarr;</div>
        <?php endif;?>
        <div class='step app-<?php echo $cloudApp->id;?> <?php echo $active;?>'>
          <div class='step-no <?php echo $active;?>'><?php echo $order;?></div>
          <div class='step-title'><span id='<?php echo $cloudApp->alias;?>-status'></span><?php echo $cloudApp->alias;?></div>
        </div>
        <?php endforeach;?>
      </div>
      <div id='terminal'>
      </div>
      <div class='text-center'>
        <span class='progress load-indicator loading'></span>
        <span class='progress-message'></span>
      </div>
      <div class='error-message text-red text-center'></div>
      <div class='form-actions text-center'>
        <?php echo html::commonButton($lang->solution->retryInstall, "id='retryInstallBtn' class='hide'", 'btn btn-primary btn-wide');?>
        <?php echo html::a('https://www.qucheng.com/book/Installation-manual/quick-install-6.html', $lang->help, '', 'id="help" class="btn btn-primary btn-wide" target="_blank"');?>
      </div>
    </div>
</div>
</div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.lite.html.php';?>
