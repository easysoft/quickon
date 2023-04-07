<?php
/**
 * The control file of index module of QuCheng.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     index
 * @version     $Id$
 * @link        https://www.qucheng.com
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
        if($this->config->edition != 'open')
        {
            if(empty($_SESSION['k8stag'])) $this->session->set('k8stag', $this->loadModel('system')->getK8sTag());
            $this->loadModel('system')->checkOutdate();
        }

        $this->view->title         = $this->lang->index->common;
        $this->view->open          = helper::safe64Decode($open);
        $this->view->shouldUpgrade = version_compare($this->session->platformLatestVersion->version, $this->config->platformVersion, '>');

        $this->display();
    }
}
