<?php
/**
 * The model file of company module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     company
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
?>
<?php
class companyModel extends model
{
    /**
     * Set menu.
     *
     * @param  int    $dept
     * @access public
     * @return void
     */
    public function setMenu($dept = 0)
    {
        /*
        common::setMenuVars($this->lang->company->menu, 'name', array($this->app->company->name));
        common::setMenuVars($this->lang->company->menu, 'addUser', array($dept));
        common::setMenuVars($this->lang->company->menu, 'batchAddUser', array($dept));
         */
    }

    /**
     * Get company list.
     *
     * @access public
     * @return void
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
    }

    /**
     * Get the first company.
     *
     * @access public
     * @return null | array
     */
    public function getFirst()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->orderBy('id')->limit(1)->fetch();
    }

    /**
     * Get the admin of company.
     *
     * @access public
     * @return null | array
     */
    public function getAdmin()
    {
        $company = $this->getFirst();
        if(!$company) return '';
        $adminAccount = explode(',', trim($company->admins, ','))[0];

        return $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($adminAccount)->limit(1)->fetch();
    }

    /**
     * Get company info by id.
     *
     * @param  int    $companyID
     * @access public
     * @return object
     */
    public function getByID($companyID = '')
    {
        return $this->dao->findById((int)$companyID)->from(TABLE_COMPANY)->fetch();
    }

    /**
     * Get users.
     *
     * @param  string $type
     * @param  int    $queryID
     * @param  int    $deptID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUsers($browseType = 'inside', $type = '', $queryID = 0, $deptID = 0, $sort = '', $pager = null)
    {
        /* Get users. */
        if($type == 'bydept')
        {
            $childDeptIds = $this->loadModel('dept')->getAllChildID($deptID);
            return $this->dept->getUsers($browseType, $childDeptIds, $pager, $sort);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('userQuery', $query->sql);
                    $this->session->set('userForm', $query->form);
                }
                else
                {
                    $this->session->set('userQuery', ' 1 = 1');
                }
            }
            return $this->loadModel('user')->getByQuery($browseType, $this->session->userQuery, $pager, $sort);
        }
    }

    /**
     * Get outside companies.
     *
     * @access public
     * @return array
     */
    public function getOutsideCompanies()
    {
        $companies = $this->dao->select('id, name')->from(TABLE_COMPANY)->where('id')->ne(1)->fetchPairs();
        return array('' => '') + $companies;
    }

    /**
     * Get company-user pairs.
     *
     * @access public
     * @return array
     */
    public function getCompanyUserPairs()
    {
        $pairs = $this->dao->select("t1.account, CONCAT_WS('/', t2.name, t1.realname)")->from(TABLE_USER)->alias('t1')
            ->leftJoin(TABLE_COMPANY)->alias('t2')
            ->on('t1.company = t2.id')
            ->fetchPairs();

        return $pairs;
    }

    /**
     * Update a company.
     *
     * @access public
     * @return void
     */
    public function update()
    {
        $company = fixer::input('post')
            ->stripTags('name')
            ->get();
        if($company->website  == 'http://') $company->website  = '';
        if($company->backyard == 'http://') $company->backyard = '';
        $companyID = $this->app->company->id;
        $this->dao->update(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->edit->requiredFields, 'notempty')
            ->batchCheck('name', 'unique', "id != '$companyID'")
            ->where('id')->eq($companyID)
            ->exec();
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->company->browse->search['actionURL'] = $actionURL;
        $this->config->company->browse->search['queryID']   = $queryID;
        $this->config->company->browse->search['params']['dept']['values']    = array('' => '') + $this->loadModel('dept')->getOptionMenu();
        $this->config->company->browse->search['params']['visions']['values'] = $this->loadModel('user')->getVisionList();

        $this->loadModel('search')->setSearchParams($this->config->company->browse->search);
    }

    /**
     * Judge a account is admin or not.
     *
     * @param  int    $account
     * @static
     * @access public
     * @return boolean
     */
    public static function isAccountAdmin($account)
    {
        global $app;
        return strpos($app->company->admins, ',' . $account . ',') !== false;
    }
}
