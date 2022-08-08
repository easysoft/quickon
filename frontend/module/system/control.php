<?php
/**
 * The control file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class system extends control
{
    public function index()
    {

        $this->position = $this->lang->system->common;
        $this->display();
    }

    public function adminer()
    {

        $this->view->position[] = $this->lang->system->dbManagement;

        $this->view->title = $this->lang->system->dbManagement;

        $this->display();
    }
}

