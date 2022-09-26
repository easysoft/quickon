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
     * Get system space.
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function getSystemSpace($account)
    {
        $k8space = getenv('POD_NAMESPACE');
        if(empty($k8space)) $k8space = 'cne-system';

        $sysSpace = $this->dao->select('*')->from(TABLE_SPACE)->where('k8space')->eq($k8space)->andWhere('owner')->eq($account)->fetch();
        if($sysSpace) return $sysSpace;

        $spaceData = new stdClass;
        $spaceData->name      = $this->lang->space->systemSpace;
        $spaceData->owner     = $account;
        $spaceData->k8space   = $k8space;
        $spaceData->default   = 0;
        $spaceData->createdAt = date('Y-m-d H:i:s');

        $this->dao->insert(TABLE_SPACE)->data($spaceData)->autoCheck()->exec();

        return $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($this->dao->lastInsertId())->fetch();
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
    public function getSpaceInstances($spaceID, $status = 'all', $searchName = '', $pager = null)
    {
        $deadline = date('Y-m-d H:i:s', strtotime("-{$this->config->demoAppLife} minutes"));

        $space = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->eq($spaceID)->fetch();

        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('space')->eq($spaceID)
            ->beginIF($status !== 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF(!empty($searchName))->andWhere('name')->like("%{$searchName}%")->fi()
            ->beginIF(commonModel::isDemoAccount())->andWhere('createdAt')->gt($deadline)->fi()
            ->orderBy('id desc')->page($pager)->fetchAll('id');

        $this->loadModel('store');
        foreach($instances as $instance) $instance->latestVersion = $this->store->appLatestVersion($instance->appID, $instance->version);

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
        return  $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->eq($id)
            ->andWhere('owner')->eq($this->app->user->account)
            ->fetch();
    }
}
