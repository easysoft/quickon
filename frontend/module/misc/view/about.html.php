<?php include '../../common/view/header.lite.html.php';?>
<main id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='main-header'>
        <h2><?php echo $lang->misc->qucheng->labels['about'];?></h2>
      </div>
      <table class='table table-form'>
        <tr>
          <td class='text-center w-160px'>
            <img class="about-logo" src='<?php echo $config->webRoot . 'theme/default/images/main/' . $lang->logoImg;?>' />
            <h4>
              <?php if(trim($config->visions, ',') == 'lite'):?>
              <?php echo $lang->liteName.$config->liteVersion; ?>
              <?php else:?>
              <?php printf($lang->misc->qucheng->version, $config->version); ?>
              <?php endif;?>
            </h4>
          </td>
          <td>
            <p style="font-size:16px; text-align:center;"> <?php echo $lang->quchengSummary;?></p>
          </td>
        </tr>
        <tr>
          <td colspan='2' class="copyright text-right text-middle">
            <div class='pull-left'><?php echo $lang->designedByAIUX;?></div>
            <?php echo $lang->misc->copyright;?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
