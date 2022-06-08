<?php
/**
 * The control file of index module of QuCheng.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     index
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
class index extends control
{
    /**
     * Construct function, load project, product.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The index page of whole system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index($open = '')
    {
        $latestVersionList = array();
        if(isset($this->config->global->latestVersionList)) $latestVersionList = json_decode($this->config->global->latestVersionList);

        $this->view->title             = $this->lang->index->common;
        $this->view->open              = helper::safe64Decode($open);
        $this->view->latestVersionList = $latestVersionList;

        $this->display();
    }
}
