<?php
/**
 * The control file of solution module of QuCheng.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class solution extends control
{
    /**
     * Show solution list of market.
     *
     * @access public
     * @return void
     */
    public function browseMarket()
    {
        $this->view->title = $this->lang->solution->common;

        $this->view->solutionList = $this->loadModel('store')->searchSolutions();

        $this->display();
    }

    /**
     * Show installled solution list.
     *
     * @access public
     * @return void
     */
    public function browse()
    {
        $this->view->title = $this->lang->solution->common;

        $this->view->solutionList = $this->solution->search();

        $this->display();
    }

    /**
     * Show solution detail of market.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function viewMarket($id)
    {
        $this->view->title      = $this->lang->solution->market->view;
        $this->view->solution   = $this->loadModel('store')->getSolutionByID($id);
        $this->view->components = $this->loadModel('store')->solutionConfigByID($id);

        $this->display();
    }

    /**
     * Show installed solution deail.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $solution = $this->solution->getByID($id);
        if($solution->status != 'installed') return printf(js::locate($this->inLink('progress', "id=$id")));

        $this->view->title    = $this->lang->solution->view;
        $this->view->solution = $solution;

        $this->display();
    }

    /**
     * Edit solution app name.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function editName($id)
    {
        $solution = $this->solution->getByID($id);

        if(!empty($_POST))
        {
            $this->solution->updateName($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
        }

        $this->view->title    = $solution->name;
        $this->view->solution = $solution;

        $this->display();
    }

    /**
     * Show installing solution progress.
     *
     * @param  int    $cloudSolutionID
     * @access public
     * @return void
     */
    public function install($cloudSolutionID)
    {
        $cloudSolution = $this->loadModel('store')->getSolutionByID($cloudSolutionID);
        $components    = $this->loadModel('store')->solutionConfigByID($cloudSolutionID);
        if($_POST)
        {
            $solution = $this->solution->create($cloudSolution, $components);
            if(dao::isError()) $this->send(array('result' => 'failure', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->solution->notices->success, 'data' => $solution, 'locate' => $this->inLink('progress', "id=$solution->id")));
        }

        $this->view->title         = $this->lang->solution->install;
        $this->view->cloudSolution = $cloudSolution;
        $this->view->components    = $components;

        $this->display();
    }

    /**
     * Start install by ajax.
     *
     * @param  int    $solutionID
     * @access public
     * @return void
     */
    public function ajaxInstall($solutionID)
    {
        $this->solution->install($solutionID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success', 'message' => '', 'locate' => $this->inLink('view', "id=$solutionID")));
    }

    /**
     * Ajax uninstall
     *
     * @param  int    $solutionID
     * @access public
     * @return void
     */
    public function ajaxUninstall($solutionID)
    {
        $this->solution->uninstall($solutionID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success', 'message' => '', 'locate' => $this->inLink('browse')));
    }

    /**
     * Show installation progress of solution.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function progress($id)
    {
        $solution  = $this->solution->getByID($id);

        $this->view->title    = $this->lang->solution->progress;
        $this->view->solution = $solution;

        $this->display();
    }

    /**
     * Get installing progress of solution by ajax.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function ajaxProgress($id)
    {
        $solution = $this->solution->getByID($id);
        if(in_array($solution->status, array('installing', 'installed')))
        {
            $result  = 'success';
            $message = '';

            if($solution->status == 'installing')
            {
                if((time() - strtotime($solution->updatedDate)) > 60 * 20)
                {
                    $this->solution->saveStatus($id, 'timeout');
                    $result  = 'fail';
                    $message = $this->lang->solution->errors->timeout;
                }
            }
        }
        else
        {
            $result  = 'fail';
            $message = zget($this->lang->solution->installationErrors, $solution->status, $this->lang->solution->errors->hasInstallationError);
        }

        $this->send(array('result' => $result, 'message' => $message, 'data' => json_decode($solution->components)));
    }
}
