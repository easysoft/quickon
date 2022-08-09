<?php
/**
 * The model file of system module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class systemModel extends model
{
    /**
     * Print action buttons.
     *
     * @param  object $db
     * @access public
     * @return void
     */
    public function printAction($db)
    {
        $disabled = strtolower($db->status) == 'running' ? '' : 'disabled';
        $btnHtml = html::commonButton($this->lang->system->management, "{$disabled} data-db-name='{$db->name}' data-namespace='{$db->namespace}'", 'db-login btn btn-primary');

        echo $btnHtml;
    }

}

