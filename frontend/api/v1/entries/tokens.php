<?php
/**
 * The tokens entry point of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.qucheng.com
 */
class tokensEntry extends baseEntry
{
    /**
     * POST method.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $account  = $this->request('account');
        $password = $this->request('password');

        $user = $this->loadModel('user')->identify($account, $password);

        if($user)
        {
            $this->user->login($user);
            $this->send(201, array('token' => session_id()));
        }

        $this->sendError(400, $this->app->lang->user->loginFailed);
    }
}
