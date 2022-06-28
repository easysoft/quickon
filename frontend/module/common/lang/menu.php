<?php
$lang->navIcons['my']    = "<i class='icon icon-gauge'></i>";
$lang->navIcons['store'] = "<i class='icon icon-store'></i>";
$lang->navIcons['space'] = "<i class='icon icon-apps'></i>";
$lang->navIcons['admin']     = "<i class='icon icon-home-more'></i>";

$lang->visionList['qucheng'] = '渠成平台';

/* Main Navigation. */
$lang->mainNav         = new stdclass();
$lang->mainNav->my     = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->store  = "{$lang->navIcons['store']} {$lang->store->shortCommon}|store|index|";
$lang->mainNav->space  = "{$lang->navIcons['space']} {$lang->space->shortCommon}|space|browse|";
$lang->mainNav->admin  = "{$lang->navIcons['admin']} {$lang->admin->common}|backup|index|";

$lang->dividerMenu = ',admin,';

/* Menu order. */
$lang->mainNav->menuOrder = array();
$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[7]  = 'store';
$lang->mainNav->menuOrder[9]  = 'space';
$lang->mainNav->menuOrder[65] = 'admin';

/* My menu. */
$lang->my->menu        = new stdclass();
//$lang->my->menu->index = array('link' => "$lang->dashboard|my|index");

/* Store menu. */
$lang->store->menu = new stdclass();
//$lang->store->menu->store = array('link' => "{$lang->store->cloudStore}|store|browse|");

/* Space menu. */
$lang->space->menu = new stdclass();

/* Instance menu. */
$lang->instance->menu = new stdclass();
//$lang->instance->menu->instance = array('link' => "{$lang->instance->install}|space|custominstall|");
//
/* Admin menu. */
$lang->admin->menu            = new stdclass();
//$lang->admin->menu->index     = array('link' => "$lang->indexPage|admin|index", 'alias' => 'ztcompany');
$lang->admin->menu->system    = array('link' => "{$lang->admin->system}|backup|index", 'subModule' => 'cron,backup,action,admin,search', 'exclude' => 'admin-index');

$lang->navGroup = new stdclass();
$lang->navGroup->instance = 'space';
$lang->navGroup->backup   = 'admin';
