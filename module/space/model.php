<?php
/**
 * The model file of space module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class spaceModel extends model
{
    /**
     * Get space list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSpacesByAccount($account)
    {
        return $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('owner')->eq($account)->fetchAll();
    }

    /**
     * Get user's default space by user account.
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function defaultSpace($account)
    {
        $default = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('owner')->eq($account)->orderBy('default desc')->limit(1)->fetch();

        if(empty($default)) return $this->createDefaultSpace($account);
        return $default;
    }

    /**
     * Create default space by account
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function createDefaultSpace($account)
    {
        $default = new stdclass;
        $default->name      = $this->lang->space->defaultSpace;
        $default->k8space   = 'default';
        $default->owner     = $account;
        $default->default   = true;
        $default->createdAt = date('Y-m-d H:i:s');

        $this->dao->insert(TABLE_SPACE)->data($default)->autoCheck()->exec();

        return $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($this->dao->lastInsertId())->fetch();
    }

    /**
     * Get app list in space
     *
     * @param  int    $spaceID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSpaceInstances($spaceID, $searchName = '', $pager = null)
    {
        $space     = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->eq($spaceID)->fetch();
        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('space')->eq($spaceID)
            ->beginIF(!empty($searchName))->andWhere('name')->like("%{$searchName}%")->fi()
            ->orderBy('id desc')->page($pager)->fetchAll('id');

        return $instances;
    }

    /**
     * Get space by id.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return  $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->eq($id)->fetch();
    }

    /*
     * Get space switcher.
     *
     * @param  object  $space
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher($space, $currentModule, $currentMethod)
    {
        $currentSpaceName = $space->name;

        $dropMenuLink = helper::createLink('space', 'ajaxGetDropMenu', "objectID=$space->id&module=$currentModule&method=$currentMethod");

        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentSpaceName}'><span class='text'>{$currentSpaceName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }
}
