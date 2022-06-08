<?php
/**
 * The control file of misc module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   misc
 * @version   $Id$
 * @link      https://www.qucheng.cn
 */
class misc extends control
{
    /**
     * Ping the server every 5 minutes to keep the session.
     *
     * @access public
     * @return void
     */
    public function ping()
    {
        if(mt_rand(0, 1) == 1) $this->loadModel('setting')->setSN();
        echo "<html><head><meta http-equiv=refresh' content='600' /></head><body></body></html>";
    }

    /**
     * Show php info.
     *
     * @access public
     * @return void
     */
    public function phpinfo()
    {
        phpinfo();
    }

    /**
     * Show about info.
     *
     * @access public
     * @return void
     */
    public function about()
    {
        $this->display();
    }
}
