<?php
/**
 * The control file of navigation module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Xin Zhou <zhouxin@easycorp.ltd>
 * @package   navigation
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class navigation extends control
{
    public function browse()
    {
        $this->loadModel('instance');
        $account = $this->app->user->account;

        $instances = $this->instance->getByAccount($account);
        $pinnedInstances = $this->instance->getByAccount($account, '', true);

        $this->view->title           = $this->lang->navigation->common;
        $this->view->instances       = $instances;
        $this->view->pinnedInstances = $pinnedInstances;

        $this->display();
    }

    public function ajaxGetPinnedInstance($id)
    {
        $this->loadModel('instance');
        $this->instance->pinToggle($id);
        $account = $this->app->user->account;

        $this->view->pinnedInstances = $this->instance->getByAccount($account, '', true);

        $this->display();
    }
}
