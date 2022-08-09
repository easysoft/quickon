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
    public function dbList()
    {
        $dbList = $this->loadModel('cne')->allDBList();
        if(empty($dbList)) return array();

        $k8nameList = array_column($dbList, 'name');

        $instaceList = $this->dao->select('name,k8name')->from(TABLE_INSTANCE)->where('k8name')->in($k8nameList)->fetchPairs('name', 'k8name');

    }


}

