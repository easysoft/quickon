<?php
/**
 * The control file of admin module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        http://www.qucheng.cn
 */
class admin extends control
{
    /**
     * Init company and admin
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $admin = $this->loadModel('company')->getAdmin();;
        if($admin) return print(js::locate('/'));

        if($_POST)
        {
            $_POST['company'] = $_POST['account'];
            $this->admin->init();

            if(dao::isError()) return print(js::error(dao::getError()));

            $company = $this->loadModel('company')->getFirst();
            $this->session->set('company', $company);
            return print(js::locate('/'));
        }

        $this->view->title = $this->lang->admin->initAdmin;
        $this->display();
    }
}
