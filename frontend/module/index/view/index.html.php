<?php
/**
 * The html template file of index method of index module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     index
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php
include '../../common/view/header.lite.html.php';
$this->app->loadConfig('sso');
if(!empty($config->sso->redirect)) js::set('ssoRedirect', $config->sso->redirect);

js::set('vision',        $config->vision);
js::set('navGroup',      $lang->navGroup);
js::set('appsLang',      $lang->index->app);
js::set('appsMenuItems', commonModel::getMainNavList($app->rawModule));
js::set('defaultOpen',   $open);
js::set('manualText',    $lang->manual);
js::set('manualUrl',     (!empty($config->isINT) ? $config->manualUrl['install'] : $config->manualUrl['home']));
?>
<style>
<?php if(commonModel::isTutorialMode()):?>
#menuMoreNav > li.dropdown:hover + .tooltip {display: none!important;}
#menuMoreList > li.active {position: relative;}
#menuMoreList > li.active:before {content: ' '; display: block; position: absolute; left: 100%; border-width: 5px 5px 5px 0; border-style: solid; border-color: transparent; border-right-color: #ff9800; width: 0; height: 0; top: 12px}
#menuMoreList > li.active:after {content: attr(data-tip); display: block; position: absolute; left: 100%; background-color: #f1a325; color: #fff; top: 3px; white-space: nowrap; line-height: 16px; padding: 8px 10px; margin-left: 5px; border-radius: 4px;}
<?php endif;?>
</style>
<div id='menu'>
  <nav id='menuNav'>
    <ul class='nav nav-default' id='menuLogo'>
      <li>
        <img src="<?php echo $config->webRoot . 'theme/default/images/main/' . $this->lang->logoImg;?>" />
        <span class="text"><?php echo $lang->qucheng;?></span>
      </li>
    </ul>
    <ul class='nav nav-default' id='menuMainNav'>
    </ul>
    <ul class='nav nav-default' id='menuMoreNav'>
      <li class='divider'></li>
      <li class='dropdown dropdown-hover'>
        <a title='<?php echo $lang->more; ?>'><i class='icon icon-more-circle'></i><span class='text'><?php echo $lang->more; ?></span></a>
        <ul id='menuMoreList' class='dropdown-menu fade'></ul>
      </li>
    </ul>
  </nav>
  <div class="table-col col-right">
    <div id="moreExecution" class="more-execution-show" data-ride="searchList">
      <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input empty">
        <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
        <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
      </div>
      <div class="list-group" id="executionList"></div>
    </div>
  </div>
  <div id='menuFooter'>
    <ul id="flodNav" class="nav">
      <li id='menuToggleMenu'>
        <a class='menu-toggle' data-collapse-text='<?php echo $lang->collapseMenu; ?>' data-unfold-text='<?php echo $lang->unfoldMenu; ?>'>
          <i class='icon icon-sm icon-menu-collapse'></i>
        </a>
      </li>
    </ul>
  </div>
</div>
<div id='apps'>
</div>
<div id='appsBar'>
  <ul id='bars' class='nav nav-default'></ul>
  <div id='poweredBy'>
    <div id="globalBarLogo">
      <?php echo $shouldUpgrade ? html::a(helper::createLink('backup', 'index'), "<i class='icon icon-arrow-up-circle'></i>", '', "title='{$lang->index->upgradeTo} {$this->session->platformLatestVersion->version}' class='btn btn-link'") : '';?>
      <span class='version-container'>
        <img src="<?php echo $config->webRoot . 'theme/default/images/main/' . $this->lang->logoImg;?>"/>
        <span class='version'><?php echo $lang->qucheng . ' ' . getenv('APP_VERSION') . ($config->debug ? ' (' . $config->platformVersion . ' ' . getenv('BUILD_VERSION') .')' : '');?></span>
      </span>
    </div>
  </div>
</div>
<?php js::set('searchAB', $lang->searchAB);?>
<?php js::set('searchObjectList', ',' . implode(',', array_keys($lang->searchObjects)) . ',');?>
<?php js::set('searchCommon', $lang->index->search);?>

<script>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
