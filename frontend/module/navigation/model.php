<?php
/**
 * The model file of navigation module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Xin Zhou <zhouxin@easycrop.ltd>
 * @package   navigation
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class navigationModel extends model
{
    /**
     * The construct of navigation model.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a app by id.
     * 
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_APP)->where('id')->eq($id)->fetch();
    }

    /**
     * Create a app.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $app = fixer::input('post')
            ->setDefault('pinned', 0)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdAt', helper::now())
            ->get();

        $this->dao->insert(TABLE_APP)->data($app)->autoCheck()->check('title', 'notempty')->exec();
    }

    /**
     * Get apps.
     * 
     * @param  string $pinned
     * @param  string $searchParam
     * @access public
     * @return array
     */
    public function getApps($pinned = '', $searchParam = '')
    {
        $apps = $this->dao->select('*')->from(TABLE_APP)
            ->beginIF($pinned)->where('pinned')->eq((int)$pinned)->fi()
            ->beginIF($searchParam)->andWhere('title')->like("%{$searchParam}%")->fi()
            ->fetchAll();
        return $apps;
    }

    /**
     * Toggle pinned or unpinned.
     * 
     * @param  int    $appID
     * @access public
     * @return void
     */
    public function pinToggle($appID)
    {
        $app = $this->getByID($appID);
        $pinned = $app->pinned == '0' ? '1' : '0';
        $this->dao->update(TABLE_APP)->set('pinned')->eq($pinned)->where('id')->eq($appID)->exec();
    }

}
