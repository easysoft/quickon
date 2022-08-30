<?php
/**
 * The model file of store module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(Beijing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjanhua@easycorp.ltd>
 * @package   store
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class storeModel extends model
{
    /**
     * Get switcher of browse page of store.
     *
     * @access public
     * @return string
     */
    public function getBrowseSwitcher()
    {
        $title = $this->lang->store->cloudStore;

        if($this->config->cloud->api->switchChannel) $title .= '（' . ($this->config->cloud->api->channel == 'stable' ? $this->lang->store->stableChannel : $this->lang->store->testChannel) . '）';

        $output  = "<div class='btn-group header-btn'>";

        if($this->config->cloud->api->switchChannel)
        {
            $stableActive = $this->config->cloud->api->channel == 'stable' ? 'active' : '';
            $testActive   = $this->config->cloud->api->channel != 'stable' ? 'active' : '';

            $output .= "<a href='javascript:;' class='btn'  data-toggle='dropdown'>{$title}<span class='caret' style='margin-bottom: -1px;margin-left:5px;'></span></a>";
            $output .= "<ul class='dropdown-menu'>";
            $output .= "<li class='{$stableActive}'>" . html::a(helper::createLink('store', 'browse', 'recTotal=0&perPage=20&pageID=1&channel=stable'), $this->lang->store->stableChannel) ."</li>";
            $output .= "<li class='{$testActive}'>" . html::a(helper::createLink('store', 'browse', 'recTotal=0&perPage=20&pageID=1&channel=test'), $this->lang->store->testChannel) ."</li>";
            $output .= "</ul>";
        }
        else
        {
            $output .= "<a href='javascript:;' class='btn'  data-toggle='dropdown'>{$title}</a>";
        }

        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of app view page of store.
     *
     * @param  object $app
     * @access public
     * @return string
     */
    public function getAppViewSwitcher($app)
    {
        $output  = $this->getBrowseSwitcher();
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('store', 'appview', "id=$app->id"), $app->alias, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get app dynamic news from Qucheng offical site.
     *
     * @param  object $cloudApp
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return mixed
     */
    public function appDynamic($cloudApp, $pageID = 1, $recPerPage = 20)
    {
        $url = $this->config->store->quchengSiteHost . '/article-apibrowse.html';

        $headers = array();
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-type: multipart/form-data';

        $apiParams = array();
        $apiParams['alias']      = strtolower(str_replace('-', '', $cloudApp->chart));
        $apiParams['page']       = $pageID;
        $apiParams['recPerPage'] = $recPerPage;

        $result = commonModel::apiPost($url, $apiParams, $headers);
        if($result && $result->code == 200) return $result->data;

        return array();
    }
}
