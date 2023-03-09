</div><?php /* end '.outer' in 'header.html.php'. */ ?>
<script>
$.initSidebar();
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>

<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php if($onlybody != 'yes' and $this->app->viewType != 'xhtml'):?>
</main><?php /* end '#wrap' in 'header.html.php'. */ ?>
<script>

<?php if(!empty($config->sso->redirect)):?>
<?php
$ranzhiAddr = $config->sso->addr;
$ranzhiURL  = substr($ranzhiAddr, 0, strrpos($ranzhiAddr, '/sys/'));
?>
<?php if(!empty($ranzhiURL)):?>
$(function(){ redirect('<?php echo $ranzhiURL?>', '<?php echo $config->sso->code?>'); });
<?php endif;?>
<?php endif;?>
</script>

<?php endif;?>
<?php
if(isset($pageJS)) js::execute($pageJS);  // load the js for current page.

/* Load hook files for current page. */
$extensionRoot = $this->app->getExtensionRoot();
if($this->config->vision != 'open')
{
    $extHookRule  = $extensionRoot . $this->config->edition . '/common/ext/view/footer.*.hook.php';
    $extHookFiles = glob($extHookRule);
    if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
}
if($this->config->vision == 'lite')
{
    $extHookRule  = $extensionRoot . $this->config->vision . '/common/ext/view/footer.*.hook.php';
    $extHookFiles = glob($extHookRule);
    if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
}
$extHookRule  = $extensionRoot . 'custom/common/ext/view/footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
</body>
</html>
