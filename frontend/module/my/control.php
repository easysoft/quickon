<?php
/**
 * The control file of dashboard module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     dashboard
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class my extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
        $this->loadModel('dept');
    }

    /**
     * Dashboard page.
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function index($total = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('cne');
        $this->app->loadClass('pager', true);
        $pager = new pager($total, $recPerPage, $pageID);

        $instances = $this->loadModel('instance')->getByAccount($this->app->user->account, $pager);

        $account = commonModel::isDemoAccount() ? $this->app->user->account : 'all';
        $actions = $this->loadModel('action')->getDynamic($account, 'today');

        $this->view->position[] = $this->lang->my->common;

        $this->view->title            = $this->lang->my->common;
        $this->view->instances        = $instances;
        $this->view->actions          = $actions;
        $this->view->cneMetrics       = $this->cne->cneMetrics();
        $this->view->instancesMetrics = $this->cne->instancesMetrics($instances);
        $this->view->pager            = $pager;

        $this->display();
    }

    /**
     * Change password
     *
     * @access public
     * @return void
     */
    public function changePassword()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));
        if(!empty($_POST))
        {
            $this->user->updatePassword($this->app->user->id);
            if(dao::isError()) return print(js::error(dao::getError()));
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('my', 'index'), 'parent.parent'));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
        $this->view->position[] = $this->lang->my->changePassword;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();

        $this->display();
    }

    /**
     * Edit profile
     *
     * @access public
     * @return void
     */
    public function editProfile()
    {
        if($this->app->user->account == 'guest')
        {
            echo js::alert('guest'), js::locate('back');
            return;
        }
        if(!empty($_POST))
        {
            $_POST['account'] = $this->app->user->account;
            $_POST['groups']  = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->fetchPairs('group', 'group');
            $this->user->update($this->app->user->id);
            if(dao::isError()) helper::end(js::error(dao::getError()));
            echo js::locate($this->createLink('my', 'profile'), 'parent');
            return;
        }

        $this->app->loadConfig('user');
        $this->app->loadLang('user');


        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->position[] = $this->lang->my->editProfile;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * View my profile.
     *
     * @access public
     * @return void
     */
    public function profile()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        $this->app->loadConfig('user');
        $this->app->loadLang('user');
        $user = $this->user->getById($this->app->user->account);

        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]   = $this->lang->my->profile;
        $this->view->user         = $user;
        $this->display();
    }

    /**
     * Upload avatar.
     *
     * @access public
     * @return void
     */
    public function uploadAvatar()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $result = $this->loadModel('user')->uploadAvatar();
            $this->send($result);
        }
    }
}
