<?php
/**
 * The control file of space module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class space extends control
{
    /**
     * Browse departments and users of a space.
     *
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
      @access public
     * @return void
     */
    public function browse($spaceID = null, $browseType = 'bycard', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('instance');

        if($spaceID)
        {
            $space = $this->space->getByID($spaceID);
        }
        else
        {
            $space = $this->space->defaultSpace($this->app->user->account);
        }

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $instances = $this->space->getSpaceInstances($space->id, $pager);

        $this->lang->switcherMenu = $this->space->getSwitcher($space, 'space', 'browse');

        $this->view->title        = $this->lang->space->common;
        $this->view->position[]   = $this->lang->space->common;
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->instances    = $instances;
        $this->view->currentSpace = $space;
        $this->view->spaces       = $this->space->getSpacesByAccount($this->app->user->account);

        $this->display();
    }

    /**
     * Ajax get space drop menu.
     *
     * @param  int     $spaceID
     * @param  string  $module
     * @param  string  $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($spaceID, $module, $method)
    {
        $spaces = $this->space->getSpacesByAccount($this->app->user->account);

        $this->view->spaceID = $spaceID;
        $this->view->spaces  = $spaces;
        $this->view->module  = $module;
        $this->view->method  = $method;

        $this->display();
    }
}
