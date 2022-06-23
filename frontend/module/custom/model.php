<?php
/**
 * The model file of custom module of QuChange.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     custom
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class customModel extends model
{
    /**
     * Get all custom lang.
     *
     * @access public
     * @return array
     */
    public function getAllLang()
    {
        $currentLang = $this->app->getClientLang();

        try
        {
            $sql  = $this->dao->select('*')->from(TABLE_LANG)->where('`lang`')->in("$currentLang,all")->andWhere('vision')->eq($this->config->vision)->orderBy('lang,id')->get();
            $stmt = $this->dbh->query($sql);

            $allCustomLang = array();
            while($row = $stmt->fetch()) $allCustomLang[$row->id] = $row;
        }
        catch(PDOException $e)
        {
            return false;
        }

        $sectionLang = array();
        foreach($allCustomLang as $customLang)
        {
            $sectionLang[$customLang->module][$customLang->section][$customLang->lang] = $customLang->lang;
        }

        $processedLang = array();
        foreach($allCustomLang as $id => $customLang)
        {
            if(isset($sectionLang[$customLang->module][$customLang->section]['all']) && isset($sectionLang[$customLang->module][$customLang->section][$currentLang]) && $customLang->lang == 'all') continue;
            $processedLang[$customLang->module][$customLang->section][$customLang->key] = $customLang->value;
        }

        return $processedLang;
    }

    /**
     * Build menu data from config
     * @param  object          $allMenu
     * @param  string | array  $customMenu
     * @access public
     * @return array
     */
    public static function setMenuByConfig($allMenu, $customMenu, $module = '')
    {
        global $app, $lang, $config;
        $menu           = array();
        $menuModuleName = $module;
        $order          = 1;
        $customMenuMap  = array();
        $tab            = $app->tab;
        $isTutorialMode = commonModel::isTutorialMode();

        if($customMenu)
        {
            if(is_string($customMenu))
            {
                $customMenuItems = explode(',', $customMenu);
                foreach($customMenuItems as $customMenuItem)
                {
                    $item = new stdclass();
                    $item->name   = $customMenuItem;
                    $item->order  = $order++;
                    $item->hidden = false;
                    $customMenuMap[$item->name] = $item;
                }
                foreach($allMenu as $name => $item)
                {
                    if(!isset($customMenuMap[$name]))
                    {
                        $item = new stdclass();
                        $item->name   = $name;
                        $item->hidden = true;
                        $item->order  = $order++;
                        $customMenuMap[$name] = $item;
                    }
                }
            }
            elseif(is_array($customMenu))
            {
                foreach($customMenu as $customMenuItem)
                {
                    if(!isset($customMenuItem->order)) $customMenuItem->order = $order;
                    $customMenuMap[$customMenuItem->name] = $customMenuItem;
                    $order++;
                }
            }
        }
        elseif($module)
        {
            $menuOrder = ($module == 'main' and isset($lang->menuOrder)) ? $lang->menuOrder : (isset($lang->menu->{$module}['menuOrder']) ? $lang->menu->{$module}['menuOrder'] : array());
            if($menuOrder)
            {
                ksort($menuOrder);
                foreach($menuOrder as $name)
                {
                    $item = new stdclass();
                    $item->name   = $name;
                    $item->hidden = false;
                    $item->order  = $order++;
                    $customMenuMap[$name] = $item;
                }
            }
        }

        /* Merge fileMenu and customMenu. */
        foreach($customMenuMap as $name => $item)
        {
            if(is_object($allMenu) and !isset($allMenu->$name)) $allMenu->$name = $item;
            if(is_array($allMenu)  and !isset($allMenu[$name])) $allMenu[$name] = $item;
        }

        foreach($allMenu as $name => $item)
        {
            if(is_object($item)) $item = (array)$item;

            $label     = '';
            $module    = '';
            $method    = '';
            $class     = '';
            $subModule = '';
            $subMenu   = '';
            $dropMenu  = '';
            $alias     = '';
            $exclude   = '';

            $link = (is_array($item) and isset($item['link'])) ? $item['link'] : $item;
            /* The variable of item has not link and is not link then ignore it. */
            if(!is_string($link)) continue;

            $label   = $link;
            $hasPriv = true;
            if(strpos($link, '|') !== false)
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
                $hasPriv = commonModel::hasPriv($module, $method);
                /* Fix bug #20464 */
                if(!$hasPriv and is_array($item) and isset($item['subMenu']))
                {
                    foreach($item['subMenu'] as $subMenu)
                    {
                        if(!isset($subMenu['link']) or strpos($subMenu['link'], '|') === false) continue;
                        list($subLabel, $module, $method) = explode('|', $subMenu['link']);
                        $hasPriv = commonModel::hasPriv($module, $method);
                        if($hasPriv) break;
                    }
                }

                if($module == 'execution' and $method == 'more') $hasPriv = true;
                if($module == 'project' and $method == 'other')  $hasPriv = true;
            }

            if($isTutorialMode || $hasPriv)
            {
                $itemLink = '';
                if($module && $method)
                {
                    $itemLink = array('module' => $module, 'method' => $method);
                    if(isset($link[3])) $itemLink['vars'] = $link[3];
                    if(is_array($item) and isset($item['target'])) $itemLink['target'] = $item['target'];
                }

                if(is_array($item))
                {
                    if(isset($item['class']))     $class     = $item['class'];
                    if(isset($item['subModule'])) $subModule = $item['subModule'];
                    if(isset($item['subMenu']))   $subMenu   = $item['subMenu'];
                    if(isset($item['dropMenu']))  $dropMenu  = $item['dropMenu'];
                    if(isset($item['alias']))     $alias     = $item['alias'];
                    if(isset($item['exclude']))   $exclude   = $item['exclude'];
                }

                $hidden = isset($customMenuMap[$name]) && isset($customMenuMap[$name]->hidden) && $customMenuMap[$name]->hidden;

                if(is_array($item) and (isset($item['subMenu']) or isset($item['dropMenu'])))
                {
                    foreach(array('subMenu', 'dropMenu') as $key)
                    {
                        if(!isset($item[$key])) continue;
                        foreach($item[$key] as $subItem)
                        {
                            if(isset($subItem->link['module']) && isset($subItem->link['method']))
                            {
                                $subItem->hidden = !common::hasPriv($subItem->link['module'], $subItem->link['method']);
                            }
                        }
                        if(isset($customMenuMap[$name]->$key))
                        {
                            foreach($customMenuMap[$name]->$key as $subItem)
                            {
                                if(isset($subItem->hidden) && isset($item[$key][$subItem->name])) $item[$key][$subItem->name]->hidden = $subItem->hidden;
                            }
                        }
                    }
                }

                if(strpos($name, 'QUERY') === 0 and !isset($customMenuMap[$name])) $hidden = false;

                $menuItem = new stdclass();
                $menuItem->name  = $name;
                $menuItem->link  = $itemLink;
                $menuItem->text  = $label;
                $menuItem->order = (isset($customMenuMap[$name]) && isset($customMenuMap[$name]->order) ? $customMenuMap[$name]->order : $order++);
                if($hidden)   $menuItem->hidden    = $hidden;
                if($class)    $menuItem->class     = $class;
                if($subModule)$menuItem->subModule = $subModule;
                if($subMenu)  $menuItem->subMenu   = $subMenu;
                if($dropMenu) $menuItem->dropMenu  = $dropMenu;
                if($alias)    $menuItem->alias     = $alias;
                if($exclude)  $menuItem->exclude   = $exclude;
                if($isTutorialMode) $menuItem->tutorial = true;

                /* Hidden menu by config in mobile. */
                if($app->viewType == 'mhtml' and isset($config->custom->moblieHidden[$menuModuleName]) and in_array($name, $config->custom->moblieHidden[$menuModuleName])) $menuItem->hidden = 1;

                while(isset($menu[$menuItem->order])) $menuItem->order++;
                $menu[$menuItem->order] = $menuItem;
            }
        }

        ksort($menu, SORT_NUMERIC);

        /* Set divider in main and module menu. */
        if(!isset($lang->$tab->menuOrder)) $lang->$tab->menuOrder = array();
        ksort($lang->$tab->menuOrder, SORT_NUMERIC);

        $group = 0;
        $dividerOrders = array();
        foreach($lang->$tab->menuOrder as $name)
        {
            if(isset($lang->$tab->dividerMenu) and strpos($lang->$tab->dividerMenu, ",{$name},") !== false) $group++;
            $dividerOrders[$name] = $group;
        }

        $isFirst = true; // No divider before First item.
        $group   = 0;
        foreach($menu as $item)
        {
            if(isset($dividerOrders[$item->name]) and $dividerOrders[$item->name] > $group)
            {
                $menu[$item->order]->divider = $isFirst ? false : true;
                $group = $dividerOrders[$item->name];
            }
            else
            {
                $isFirst = false;
                $menu[$item->order]->divider = false;
            }
        }

        return array_values($menu);
    }

    /**
     * Get module menu data, if module is 'main' then return main menu.
     * @param  string   $module
     * @param  boolean  $rebuild
     * @access public
     * @return array
     */
    public static function getModuleMenu($module = 'main', $rebuild = false)
    {
        global $app, $lang, $config;

        if(empty($module)) $module = 'main';

        $allMenu = new stdclass();
        if($module == 'main' and !empty($lang->menu)) $allMenu = $lang->menu;
        if($module != 'main' and isset($lang->menu->$module) and isset($lang->menu->{$module}['subMenu'])) $allMenu = $lang->menu->{$module}['subMenu'];
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);
        $flowModule = $config->global->flow . '_' . $module;
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        if($module == 'my' && empty($config->global->scoreStatus)) unset($allMenu->score);

        $menu = self::setMenuByConfig($allMenu, $customMenu, $module);

        return $menu;
    }

    /**
     * Get main menu data
     * @param  boolean $rebuild
     * @access public
     * @return array
     */
    public static function getMainMenu($rebuild = false)
    {
        return self::getModuleMenu('main', $rebuild);
    }

    /**
     * Get feature menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public static function getFeatureMenu($module, $method)
    {
        global $app, $lang, $config;
        $app->loadLang($module);
        customModel::mergeFeatureBar($module, $method);

        $configKey  = $config->global->flow . '_feature_' . $module . '_' . $method;
        $allMenu    = isset($lang->$module->featureBar[$method]) ? $lang->$module->featureBar[$method] : null;
        $customMenu = '';
        if(!commonModel::isTutorialMode() && isset($config->customMenu->$configKey)) $customMenu = $config->customMenu->$configKey;
        if(!empty($customMenu) && is_string($customMenu)) $customMenu = json_decode($customMenu);
        return $allMenu ? self::setMenuByConfig($allMenu, $customMenu) : null;
    }
}
