<?php
$filter                    = new stdclass();
$filter->rules             = new stdclass();
$filter->rules->md5        = '/^[a-z0-9]{32}$/';
$filter->rules->base64     = '/^[a-zA-Z0-9\+\/\=]+$/';
$filter->rules->checked    = '/^[0-9,\-]+$/';
$filter->rules->idList     = '/^[0-9\|]+$/';
$filter->rules->lang       = '/^[a-zA-Z_\-]+$/';
$filter->rules->any        = '/./';
$filter->rules->orderBy    = '/^\w+_(desc|asc)$/i';
$filter->rules->browseType = '/^by\w+$/i';
$filter->rules->word       = '/^\w+$/';
$filter->rules->paramName  = '/^[a-zA-Z0-9_\.]+$/';
$filter->rules->paramValue = '/^[a-zA-Z0-9=_,`#+\^\/\.%\*\|\x7f-\xff\-]+$/';

$filter->default             = new stdclass();
$filter->default->moduleName = 'code';
$filter->default->methodName = 'code';
$filter->default->paramName  = 'reg::paramName';
$filter->default->paramValue = 'reg::paramValue';

$filter->default->get['onlybody']              = 'equal::yes';
$filter->default->get['HTTP_X_REQUESTED_WITH'] = 'equal::XMLHttpRequest';

$filter->default->cookie['lang']        = 'reg::lang';
$filter->default->cookie['theme']       = 'reg::word';
$filter->default->cookie['fingerprint'] = 'reg::word';
$filter->default->cookie['hideMenu']    = 'equal::true';
$filter->default->cookie['tab']         = 'reg::word';
$filter->default->cookie['goback']      = 'reg::any';

$filter->my           = new stdclass();
$filter->api          = new stdclass();
$filter->upgrade      = new stdclass();
$filter->misc         = new stdclass();
$filter->user         = new stdclass();
$filter->block        = new stdclass();
$filter->search       = new stdclass();
$filter->tree         = new stdclass();

$filter->block->default           = new stdclass();
$filter->block->main              = new stdclass();
$filter->api->index               = new stdClass();
$filter->api->create              = new stdClass();
$filter->api->edit                = new stdClass();
$filter->misc->checkupdate        = new stdclass();
$filter->upgrade->license         = new stdclass();
$filter->user->login              = new stdclass();
$filter->user->edit               = new stdclass();
$filter->user->ajaxgetmore        = new stdclass();
$filter->search->index            = new stdclass();
$filter->tree->browse             = new stdclass();

$filter->api->index->get['libID']                   = 'int';
$filter->api->index->get['module']                  = 'int';
$filter->api->index->get['apiID']                   = 'int';
$filter->api->index->get['version']                 = 'int';
$filter->api->create->get['libID']                  = 'int';
$filter->api->create->get['module']                 = 'int';
$filter->api->create->get['apiID']                  = 'int';
$filter->api->edit->get['libID']                    = 'int';
$filter->api->edit->get['module']                   = 'int';
$filter->api->edit->get['apiID']                    = 'int';

$filter->user->login->cookie['keepLogin'] = 'equal::on';

$filter->block->default->get['hash']    = 'reg::md5';
$filter->block->main->get['blockid']    = 'code';
$filter->block->main->get['blockTitle'] = 'reg::any';
$filter->block->main->get['entry']      = 'code';
$filter->block->main->get['lang']       = 'reg::lang';
$filter->block->main->get['mode']       = 'code';
$filter->block->main->get['dashboard']  = 'code';
$filter->block->main->get['param']      = 'reg::base64';
$filter->block->main->get['sso']        = 'reg::base64';

$filter->misc->checkupdate->get['browser'] = 'code';
$filter->misc->checkupdate->get['note']    = 'reg::base64';

$filter->upgrade->license->get['agree'] = 'equal::true';

$filter->user->login->get['account']      = 'account';
$filter->user->login->get['lang']         = 'reg::lang';
$filter->user->login->get['password']     = 'reg::any';
$filter->user->edit->get['from']          = 'reg::word';
$filter->user->ajaxgetmore->get['search'] = 'reg::any';
$filter->user->ajaxgetmore->get['limit']  = 'int';
$filter->user->ajaxgetmore->get['params'] = 'reg::base64';

$filter->search->index->get['words'] = 'reg::any';
$filter->search->index->get['type']  = 'code';

