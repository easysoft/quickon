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
    public function getByID($id, $type = 'app')
    {
        if($type == 'app')
        {
            return $this->dao->select('*')->from(TABLE_NAVINSTANCE)->where('id')->eq($id)->fetch();
        }
        else
        {
            return $this->loadModel('instance')->getByID($id);
        }
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

        $this->dao->insert(TABLE_NAVINSTANCE)->data($app)->autoCheck()->check('title', 'notempty')->exec();
    }

    /**
     * Update a app.
     *
     * @param  int    $id
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function update($id, $type)
    {
        $app = fixer::input('post')->remove('post,logo')->get();
        if($type == 'app') $this->dao->update(TABLE_NAVINSTANCE)->data($app)->where('id')->eq($id)->autoCheck()->exec();
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
        $apps = $this->dao->select('*')->from(TABLE_NAVINSTANCE)
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
        $this->dao->update(TABLE_NAVINSTANCE)->set('pinned')->eq($pinned)->where('id')->eq($appID)->exec();
    }

    /**
     * Get all navigation's setting.
     *
     * @access public
     * @return array
     */
    public function getSettings()
    {
        $settings = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('module')->eq('navigation')
            ->orWhere('`key`')->eq('allowAnonymousAccess')
            ->fetchAll();
        return $settings;
    }

    /**
     * Get a setting of navigation.
     *
     * @param  string    $field
     * @access public
     * @return object
     */
    public function getSetting($field)
    {
        if($field == 'backgroundImage')
        {
            $setting = new stdClass();
            $setting->field = 'backgroundImage';
            $setting->value = '';
            return $setting;
        }
        return $this->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq($field)->fetch();
    }

    /**
     * Change a setting.
     *
     * @param  string    $field
     * @access public
     * @return void
     */
    public function configure($field)
    {
        $post = fixer::input('post')->get();
        $value = $post->value == '1' ? 'on' : 'off';

        if($field == 'allowAnonymousAccess')
        {
            $path = 'system.common.global.allowAnonymousAccess';
        }
        else
        {
            $path = 'system.navigation.global.' . $field;
        }
        $this->loadModel('setting')->setItem($path, $value);
    }
}
