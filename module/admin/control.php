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
     * Index page.
     * @access public
     * @return void
     */
    public function index()
    {
        $this->loadModel('misc');

        $this->view->title      = $this->lang->admin->common;
        $this->view->position[] = $this->lang->admin->index;
        $this->display();
    }

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

    /**
     * Reset admin password.
     *
     * @access public
     * @return viod
     */
    public function resetPassword()
    {
        $token = zget($_SERVER, 'HTTP_TOKEN');
        if(!($token == $this->config->CNE->api->token || $token == $this->config->CNE->api->token))
        {
            header("HTTP/1.1 401");
            return print(json_encode(array('code' => 401, 'message' => 'Invalid token.')));
        }

        $requestBody = json_decode(file_get_contents("php://input"));
        $password = trim(zget($requestBody, 'password', ''));
        if(empty($password))
        {
            header("HTTP/1.1 401");
            return print(json_encode(array('code' => 401, 'message' => 'Password must be not empty.')));
        }

        $admin = $this->loadModel('company')->getAdmin();
        if(empty($admin))
        {
            header("HTTP/1.1 511");
            return print(json_encode(array('code' => 511, 'message' => 'Admin account not found, please init admin account firstly.')));
        }

        $this->dao->update(TABLE_USER)->set('password')->eq(md5($password))->autoCheck()->where('id')->eq($admin->id)->exec();
        $errorMsg = dao::isError();
        if($errorMsg)
        {
            header("HTTP/1.1 510");
            return print(json_encode(array('code' => 510, 'message' => $errorMsg)));
        }

        $this->dao->insert(TABLE_ACTION)->data(array('objectType' => 'admin', 'objectID' => $admin->id, 'actor' => $admin->account, 'action' => 'resetpassword', 'date' => date('Y-m-d H:i:s')))->autoCheck()->exec();
        return print(json_encode(array('code' => 200, 'message' => 'success', 'data' => array('account' => $admin->account))));
    }
}
