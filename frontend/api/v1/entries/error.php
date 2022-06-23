<?php
/**
 * The error entry point of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.qucheng.com
 */
class errorEntry extends Entry
{
    /**
     * 404 Not Found.
     *
     * @access public
     * @return void
     */
    public function notFound()
    {
        $this->send(404, array('error' => 'not found'));
    }
}
