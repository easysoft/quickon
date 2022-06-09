<?php
/**
 * The admin entry point of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.qucheng.cn
 */
class adminPasswordEntry extends baseEntry
{
    /**
     * PUT method.
     *
     * @access public
     * @return void
     */
    public function put()
    {
        $token = zget($_SERVER, 'HTTP_TOKEN');

        if(!($token == $this->config->CNE->api->token || $token == $this->config->CNE->api->token))
        {
            $this->send(401, array('code' => 401, 'messge' => 'Invalid token.'));
        }

        $password = $this->request('password');
        if(empty($password)) $this->send(401, array('code' => 401, 'messge' => 'Password must be not empty.'));

        $admin = $this->loadModel('company')->getAdmin();
        if(empty($admin)) $this->send(511, array('code' => 511, 'messge' => 'Admin account not found, please init admin account firstly.'));

        $this->dao->update(TABLE_USER)->set('password')->eq(md5($password))->autoCheck()->where('id')->eq($admin->id)->exec();
        $errorMsg = dao::isError();
        if($errorMsg) $this->send(510, array('code' => '510', 'messge' => $errorMsg));

        $this->send(200, array('code' => '200', 'messge' => 'success', 'data' => array('account' => $admin->account)));
    }
}
