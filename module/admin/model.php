<?php
/**
 * The model file of admin module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
?>
<?php
class adminModel extends model
{
    /**
     * Create a comapny, set admin.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $data = fixer::input('post')
            ->stripTags('company')
            ->get();

        $requiredFields = explode(',', $this->config->admin->initRequiredFields);
        foreach($requiredFields as $field)
        {
            if(empty($data->{$field}))
            {
                dao::$errors[] = $this->lang->admin->errorEmpty[$field];
                return false;
            }
        }
        if($_POST['password'] != $_POST['password2'])
        {
            dao::$errors[] = $this->lang->admin->errorDiffPasswords;
            return false;
        }

        /* Insert a company. */
        $company = $this->loadModel('company')->getFirst();
        if($company)
        {
            $this->dao->update(TABLE_COMPANY)->set('admins')->eq(",{$this->post->account},")->where('id')->eq($company->id)->exec();
        }
        else
        {
            $company = new stdclass();
            $company->name   = $data->company;
            $company->admins = ",{$this->post->account},";
            $companyID = $this->dao->insert(TABLE_COMPANY)->data($company)->autoCheck()->exec();
        }

        if(!dao::isError())
        {
            /* Set admin. */
            $admin = new stdclass();
            $admin->account  = $this->post->account;
            $admin->account  = $this->post->account;
            $admin->realname = $this->post->account;
            $admin->password = md5($this->post->password);
            $admin->gender   = 'f';
            $this->dao->replace(TABLE_USER)->data($admin)->exec();
        }
    }
}
