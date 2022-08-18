<?php
/**
 * The model file of user module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     user
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class userModel extends model
{
    /**
     * Get commiters from the user table.
     *
     * @param  string  $field
     * @access public
     * @return array
     */
    public function getCommiters($field = 'realname')
    {
        $rawCommiters = $this->dao->select('commiter, account, realname')->from(TABLE_USER)->where('commiter')->ne('')->fetchAll();
        if(!$rawCommiters) return array();

        $commiters = array();
        foreach($rawCommiters as $commiter)
        {
            $userCommiters = explode(',', $commiter->commiter);
            foreach($userCommiters as $userCommiter)
            {
                $commiters[$userCommiter] = $commiter->$field ? $commiter->$field : $commiter->account;
            }
        }

        return $commiters;
    }

    /**
     * Get user info by ID.
     *
     * @param  mix     $userID
     * @param  string  $field id|account
     * @access public
     * @return object|bool
     */
    public function getById($userID, $field = 'account')
    {
        /* Return current user when user is guest or empty to make sure pages in dashboard work fine. */
        if(empty($userID) && $this->app->user->account == 'guest') return $this->app->user;

        if($field == 'id') $userID = (int)$userID;
        if($field == 'account') $userID = str_replace(' ', '', $userID);

        $user = $this->dao->select('*')->from(TABLE_USER)->where("`$field`")->eq($userID)->fetch();
        if(!$user) return false;
        $user->last = date(DT_DATETIME1, $user->last);
        return $user;
    }

    /**
     * login function.
     *
     * @param  object    $user
     * @access public
     * @return bool|object
     */
    public function login($user)
    {
        if(!$user) return false;

        $this->cleanLocked($user->account);

        /* Authorize him and save to session. */
        $user->rights = $this->authorize($user->account);
        $user->groups = $this->getGroups($user->account);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;

        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        if(isset($user->id)) $this->loadModel('action')->create('user', $user->id, 'login');

        /* Keep login. */
        if($this->post->keepLogin) $this->keepLogin($user);

        /* Save latest version of QuCheng platform to session.  */
        $this->session->set('platformLatestVersion', $this->loadModel('cne')->platformLatestVersion());

        return $user;
    }

    /**
     * Keep the user in login state.
     *
     * @param  string    $account
     * @param  string    $password
     * @access public
     * @return void
     */
    public function keepLogin($user)
    {
        setcookie('keepLogin', 'on', $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('za', $user->account, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('zp', sha1($user->account . $user->password . $this->server->request_time), $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
    }

    /**
     * Judge a user is logon or not.
     *
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        return ($this->session->user and $this->session->user->account != 'guest');
    }

    /**
     * Get groups a user belongs to.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getGroups($account)
    {
        return $this->dao->findByAccount($account)->from(TABLE_USERGROUP)->fields('`group`')->fetchPairs();
    }

    /**
     * Get groups by visions.
     *
     * @param  array $visions
     * @access public
     * @return array
     */
    public function getGroupsByVisions($visions)
    {
        if(!is_array($visions)) return array();
        $groups = $this->dao->select('id, name, vision')->from(TABLE_GROUP)
            ->andWhere('vision')->in($visions)
            ->fetchAll('id');

        $visionList = $this->getVisionList();

        foreach($groups as $key => $group)
        {
            $groups[$key] = $group->name;
            if(count($visions) > 1) $groups[$key] = $visionList[$group->vision] . ' / ' . $group->name;
        }

        return $groups;
    }

    /**
     * Unlock the locked user.
     *
     * @param  int    $account
     * @access public
     * @return void
     */
    public function cleanLocked($account)
    {
        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq('0000-00-00 00:00:00')->where('account')->eq($account)->exec();

        unset($_SESSION['loginFails']);
        unset($_SESSION["{$account}.loginLocked"]);
    }

    /**
     * Upload avatar.
     *
     * @access public
     * @return void
     */
    public function uploadAvatar()
    {
        $uploadResult = $this->loadModel('file')->saveUpload('avatar');
        if(!$uploadResult) return array('result' => 'fail', 'message' => $this->lang->fail);

        $fileIdList = array_keys($uploadResult);
        $file       = $this->file->getByID($fileIdList[0]);

        return array('result' => 'success', 'message' => '', 'locate' => helper::createLink('user', 'cropavatar', "image={$file->id}"));
    }

    /**
     * Get users by sql.
     *
     * @param  string  $browseType inside|outside|all
     * @param  string  $query
     * @param  object  $pager
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getByQuery($browseType = 'inside', $query = '', $pager = null, $orderBy = 'id')
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($browseType == 'inside')->andWhere('type')->eq('inside')->fi()
            ->beginIF($browseType == 'outside')->andWhere('type')->eq('outside')->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Create a user.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword()) return;
        if(strtolower($_POST['account']) == 'guest') return false;

        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setDefault('type', 'inside')
            ->setDefault('company', 0)
            ->setDefault('visions', '')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->password1 == false, 'password', '')
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->join('visions', ',')
            ->remove('new, group, password1, password2, verifyPassword, passwordStrength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        if(isset($_POST['new']))
        {
            if(empty($user->company))
            {
                dao::$errors['company'][] = $this->lang->user->error->companyEmpty;
                return false;
            }

            $company = new stdClass();
            $company->name = $user->company;
            $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

            $user->company = $this->dao->lastInsertID();
        }

        if($user->type == 'outside')
        {
            $requiredFieldList = explode(',', $this->config->user->create->requiredFields);
            if(in_array('dept', $requiredFieldList))     unset($requiredFieldList[array_search('dept', $requiredFieldList)]);
            if(in_array('commiter', $requiredFieldList)) unset($requiredFieldList[array_search('commiter', $requiredFieldList)]);
            $this->config->user->create->requiredFields = implode(',', $requiredFieldList);
        }

        $this->dao->insert(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($this->config->user->create->requiredFields, 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->checkIF($this->post->email != '', 'email', 'email')
            ->exec();
        if(!dao::isError())
        {
            $userID = $this->dao->lastInsertID();

            /* Set usergroup for account. */
            if(isset($_POST['group']))
            {
                foreach($this->post->group as $groupID)
                {
                    $data          = new stdclass();
                    $data->account = $this->post->account;
                    $data->group   = $groupID;
                    $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            //$this->computeUserView($user->account);
            $this->loadModel('action')->create('user', $userID, 'Created');
            //$this->loadModel('mail');
            //if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);

            return $userID;
        }
    }

    /**
     * Batch create users.
     *
     * @param  int    $users
     * @access public
     * @return array
     */
    public function batchCreate()
    {
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) helper::end(js::alert($this->lang->user->error->verifyPassword));

        $users    = fixer::input('post')->get();
        $data     = array();
        $accounts = array();
        for($i = 1; $i < $this->config->user->batchCreate; $i++)
        {
            $users->account[$i] = trim($users->account[$i]);
            if($users->account[$i] != '')
            {
                if(strtolower($users->account[$i]) == 'guest') helper::end(js::error(sprintf($this->lang->user->error->reserved, $i)));
                $account = $this->dao->select('account')->from(TABLE_USER)->where('account')->eq($users->account[$i])->fetch();
                if($account) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $i)));
                if(in_array($users->account[$i], $accounts)) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $i)));
                if(!validater::checkAccount($users->account[$i])) helper::end(js::error(sprintf($this->lang->user->error->account, $i)));
                if($users->realname[$i] == '') helper::end(js::error(sprintf($this->lang->user->error->realname, $i)));
                if(empty($users->visions[$i])) helper::end(js::error(sprintf($this->lang->user->error->visions, $i)));
                if($users->email[$i] and !validater::checkEmail($users->email[$i])) helper::end(js::error(sprintf($this->lang->user->error->mail, $i)));
                $users->password[$i] = (isset($prev['password']) and $users->ditto[$i] == 'on' and !$this->post->password[$i]) ? $prev['password'] : $this->post->password[$i];
                if(!validater::checkReg($users->password[$i], '|(.){6,}|')) helper::end(js::error(sprintf($this->lang->user->error->password, $i)));
                $role    = $users->role[$i] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : '') : $users->role[$i];
                $visions = in_array('ditto', $users->visions[$i]) ? (isset($prev['visions']) ? $prev['visions'] : array()) : $users->visions[$i];

                /* Check weak and common weak password. */
                if(isset($this->config->safe->mode) and $this->computePasswordStrength($users->password[$i]) < $this->config->safe->mode) helper::end(js::error(sprintf($this->lang->user->error->weakPassword, $i)));
                if(!empty($this->config->safe->changeWeak))
                {
                    if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                    if(strpos(",{$this->config->safe->weak},", ",{$users->password[$i]},") !== false) helper::end(js::error(sprintf($this->lang->user->error->dangerPassword, $i, $this->config->safe->weak)));
                }

                $data[$i] = new stdclass();
                $data[$i]->dept     = $users->dept[$i] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $users->dept[$i];
                $data[$i]->account  = $users->account[$i];
                $data[$i]->type     = 'inside';
                $data[$i]->realname = $users->realname[$i];
                $data[$i]->role     = $role;
                $data[$i]->group    = in_array('ditto', isset($users->group[$i]) ? $users->group[$i] : array()) ? (isset($prev['group']) ? $prev['group'] : '') : $users->group[$i];
                $data[$i]->email    = $users->email[$i];
                $data[$i]->gender   = $users->gender[$i];
                $data[$i]->password = md5(trim($users->password[$i]));
                $data[$i]->commiter = $users->commiter[$i];
                $data[$i]->join     = empty($users->join[$i]) ? '0000-00-00' : ($users->join[$i]);
                $data[$i]->skype    = $users->skype[$i];
                $data[$i]->qq       = $users->qq[$i];
                $data[$i]->dingding = $users->dingding[$i];
                $data[$i]->weixin   = $users->weixin[$i];
                $data[$i]->mobile   = $users->mobile[$i];
                $data[$i]->slack    = $users->slack[$i];
                $data[$i]->whatsapp = $users->whatsapp[$i];
                $data[$i]->phone    = $users->phone[$i];
                $data[$i]->address  = $users->address[$i];
                $data[$i]->zipcode  = $users->zipcode[$i];
                $data[$i]->visions  = join(',', $visions);

                /* Check required fields. */
                foreach(explode(',', $this->config->user->create->requiredFields) as $field)
                {
                    $field = trim($field);
                    if(empty($field)) continue;

                    if(!isset($data[$i]->$field)) continue;
                    if(!empty($data[$i]->$field)) continue;

                    helper::end(js::error(sprintf($this->lang->error->notempty, $this->lang->user->$field)));
                }

                /* Change for append field, such as feedback. */
                if(!empty($this->config->user->batchAppendFields))
                {
                    $appendFields = explode(',', $this->config->user->batchAppendFields);
                    foreach($appendFields as $appendField)
                    {
                        if(empty($appendField)) continue;
                        if(!isset($users->$appendField)) continue;
                        $fieldList = $users->$appendField;
                        $data[$i]->$appendField = $fieldList[$i];
                    }
                }

                $accounts[$i]     = $data[$i]->account;
                $prev['dept']     = $data[$i]->dept;
                $prev['role']     = $data[$i]->role;
                $prev['group']    = $data[$i]->group;
                $prev['visions']  = $visions;
                $prev['password'] = $users->password[$i];
            }
        }

        $this->loadModel('mail');
        $userIDList = array();
        foreach($data as $user)
        {
            if(is_array($user->group))
            {
                foreach($user->group as $group)
                {
                    $groups = new stdClass();
                    $groups->account = $user->account;
                    $groups->group   = $group;
                    $this->dao->insert(TABLE_USERGROUP)->data($groups)->exec();
                }
            }
            unset($user->group);
            $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();

            /* Fix bug #2941 */
            $userID       = $this->dao->lastInsertID();
            $userIDList[] = $userID;
            $this->loadModel('action')->create('user', $userID, 'Created');

            if(dao::isError())
            {
                echo js::error(dao::getError());
                helper::end(js::reload('parent'));
            }
            else
            {
                $this->computeUserView($user->account);
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
        return $userIDList;
    }

    /**
     * Update a user.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function update($userID)
    {
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword(true)) return;

        $oldUser = $this->getById($userID, 'id');

        $userID = $oldUser->id;
        $user   = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setDefault('company', 0)
            ->setDefault('visions', '')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->join('visions', ',')
            ->remove('new, password1, password2, groups,verifyPassword, passwordStrength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        if(isset($_POST['new']))
        {
            if(empty($user->company))
            {
                dao::$errors['company'][] = $this->lang->user->error->companyEmpty;
                return false;
            }

            $company = new stdClass();
            $company->name = $user->company;
            $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

            $user->company = $this->dao->lastInsertID();
        }

        $requiredFields = array();
        foreach(explode(',', $this->config->user->edit->requiredFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $requiredFields[$field] = $field;
        }
        $requiredFields = join(',', $requiredFields);

        $this->dao->update(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->check('account', 'unique', "id != '$userID'")
            ->check('account', 'account')
            ->checkIF($this->post->email  != '', 'email',  'email')
            ->checkIF($this->post->phone  != '', 'phone',  'phone')
            ->checkIF($this->post->mobile != '', 'mobile', 'mobile')
            ->where('id')->eq((int)$userID)
            ->exec();
        if(dao::isError()) return false;

        /* If account changed, update the privilege. */
        if($this->post->account != $oldUser->account)
        {
            $this->dao->update(TABLE_USERGROUP)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
            $this->dao->update(TABLE_USERVIEW)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
            if(strpos($this->app->company->admins, ',' . $oldUser->account . ',') !== false)
            {
                $admins = str_replace(',' . $oldUser->account . ',', ',' . $this->post->account . ',', $this->app->company->admins);
                $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
                if(!dao::isError()) $this->app->user->account = $this->post->account;
            }
        }

        $oldGroups = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->fetchPairs('group', 'group');
        $newGroups = zget($_POST, 'groups', array());
        sort($oldGroups);
        sort($newGroups);

        /* If change group then reset usergroup. */
        if(join(',', $oldGroups) != join(',', $newGroups))
        {
            /* Reset usergroup for account. */
            $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->exec();

            /* Set usergroup for account. */
            if(isset($_POST['groups']))
            {
                foreach($this->post->groups as $groupID)
                {
                    $data          = new stdclass();
                    $data->account = $this->post->account;
                    $data->group   = $groupID;
                    $this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            /* Compute user view. */
            $this->computeUserView($this->post->account, true);
        }

        if(!dao::isError())
        {
            if($user->account == $this->app->user->account)
            {
                if(!empty($user->password)) $this->app->user->password = $user->password;
                if(!empty($user->realname)) $this->app->user->realname = $user->realname;
            }

            $this->loadModel('action')->create('user', $userID, 'edited');
        }
    }

    /**
     * update session random.
     *
     * @access public
     * @return void
     */
    public function updateSessionRandom()
    {
        $random = mt_rand();
        $this->session->set('rand', $random);

        return $random;
    }

    /**
     * Batch edit user.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $data = fixer::input('post')->get();
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) helper::end(js::alert($this->lang->user->error->verifyPassword));

        $oldUsers     = $this->dao->select('id, account, email')->from(TABLE_USER)->where('id')->in(array_keys($data->account))->fetchAll('id');
        $accountGroup = $this->dao->select('id, account')->from(TABLE_USER)->where('account')->in($data->account)->fetchGroup('account', 'id');

        $accounts = array();
        foreach($data->account as $id => $account)
        {
            $users[$id]['account']  = trim($account);
            $users[$id]['realname'] = $data->realname[$id];
            $users[$id]['commiter'] = $data->commiter[$id];
            $users[$id]['email']    = $data->email[$id];
            $users[$id]['type']     = $data->type[$id];
            $users[$id]['join']     = $data->join[$id];
            $users[$id]['skype']    = $data->skype[$id];
            $users[$id]['qq']       = $data->qq[$id];
            $users[$id]['dingding'] = $data->dingding[$id];
            $users[$id]['weixin']   = $data->weixin[$id];
            $users[$id]['mobile']   = $data->mobile[$id];
            $users[$id]['slack']    = $data->slack[$id];
            $users[$id]['whatsapp'] = $data->whatsapp[$id];
            $users[$id]['phone']    = $data->phone[$id];
            $users[$id]['address']  = $data->address[$id];
            $users[$id]['zipcode']  = $data->zipcode[$id];
            $users[$id]['visions']  = !empty($data->visions[$id]) ? join(',', $data->visions[$id]) : '';
            $users[$id]['dept']     = $data->dept[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $data->dept[$id];
            $users[$id]['role']     = $data->role[$id] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : 0) : $data->role[$id];

            /* Check required fields. */
            foreach(explode(',', $this->config->user->edit->requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($users[$id][$field])) continue;
                if(!empty($users[$id][$field])) continue;

                helper::end(js::error(sprintf($this->lang->error->notempty, $this->lang->user->$field)));
            }

            if(!empty($this->config->user->batchAppendFields))
            {
                $appendFields = explode(',', $this->config->user->batchAppendFields);
                foreach($appendFields as $appendField)
                {
                    if(empty($appendField)) continue;
                    if(!isset($data->$appendField)) continue;
                    $fieldList = $data->$appendField;
                    $users[$id][$appendField] = $fieldList[$id];
                }
            }

            if(isset($accountGroup[$account]) and count($accountGroup[$account]) > 1) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(in_array($account, $accounts)) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(!validater::checkAccount($users[$id]['account'])) helper::end(js::error(sprintf($this->lang->user->error->account, $id)));
            if($users[$id]['realname'] == '') helper::end(js::error(sprintf($this->lang->user->error->realname, $id)));
            if($users[$id]['email'] and !validater::checkEmail($users[$id]['email'])) helper::end(js::error(sprintf($this->lang->user->error->mail, $id)));

            $accounts[$id] = $account;
            $prev['dept']  = $users[$id]['dept'];
            $prev['role']  = $users[$id]['role'];
        }

        $this->loadModel('mail');
        foreach($users as $id => $user)
        {
            $this->dao->update(TABLE_USER)->data($user)->where('id')->eq((int)$id)->exec();
            $oldUser = $oldUsers[$id];
            if(!dao::isError())
            {
                if($this->config->mail->mta == 'sendcloud' and $user['email'] != $oldUser->email)
                {
                    $this->mail->syncSendCloud('delete', $oldUser->email);
                    $this->mail->syncSendCloud('sync', $user['email'], $user['realname']);
                }

                if($this->app->user->account == $user['account'] and !empty($user['realname'])) $this->app->user->realname = $user['realname'];
            }

            if($user['account'] != $oldUser->account)
            {
                $oldAccount = $oldUser->account;
                $this->dao->update(TABLE_USERGROUP)->set('account')->eq($user['account'])->where('account')->eq($oldAccount)->exec();
                $this->dao->update(TABLE_USERVIEW)->set('account')->eq($user['account'])->where('account')->eq($oldAccount)->exec();
                if(strpos($this->app->company->admins, ',' . $oldAccount . ',') !== false)
                {
                    $admins = str_replace(',' . $oldAccount . ',', ',' . $user['account'] . ',', $this->app->company->admins);
                    $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
                }
                if(!dao::isError() and $this->app->user->account == $oldAccount) $this->app->user->account = $users['account'];
            }
        }
    }

    /**
     * Update password
     *
     * @param  string $userID
     * @access public
     * @return void
     */
    public function updatePassword($userID)
    {
        if(!$this->checkPassword()) return;

        $user = fixer::input('post')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->remove('account, password1, password2, originalPassword, passwordStrength')
            ->get();

        if(empty($_POST['originalPassword']) or $this->post->originalPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['originalPassword'][] = $this->lang->user->error->originalPassword;
            return false;
        }

        $this->dao->update(TABLE_USER)->data($user)->autoCheck()->where('id')->eq((int)$userID)->exec();
        $this->app->user->password       = $user->password;
        $this->app->user->modifyPassword = false;
    }

    /**
     * Reset password.
     *
     * @access public
     * @return bool
     */
    public function resetPassword()
    {
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword()) return;

        $user = $this->getById($this->post->account);
        if(!$user) return false;

        $password = md5($this->post->password1);
        $this->dao->update(TABLE_USER)->set('password')->eq($password)->autoCheck()->where('account')->eq($this->post->account)->exec();
        return !dao::isError();
    }

    /**
     * Check the passwds posted.
     *
     * @access public
     * @return bool
     */
    public function checkPassword($canNoPassword = false)
    {
        $_POST['password1'] = trim($_POST['password1']);
        $_POST['password2'] = trim($_POST['password2']);
        if(!$canNoPassword and empty($_POST['password1'])) dao::$errors['password'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
        if($this->post->password1 != false)
        {
            if($this->post->password1 != $this->post->password2) dao::$errors['password'][] = $this->lang->error->passwordsame;
            if(!validater::checkReg($this->post->password1, '|(.){6,}|')) dao::$errors['password'][] = $this->lang->error->passwordrule;

            if(isset($this->config->safe->mode) and ($this->post->passwordStrength < $this->config->safe->mode)) dao::$errors['password1'][] = $this->lang->user->weakPassword;
            if(!empty($this->config->safe->changeWeak))
            {
                if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                if(strpos(",{$this->config->safe->weak},", ",{$this->post->password1},") !== false) dao::$errors['password1'][] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
            }
        }
        return !dao::isError();
    }
    /**
     * Identify a user.
     *
     * @param   string $account     the user account
     * @param   string $password    the user password or auth hash
     * @access  public
     * @return  object
     */
    public function identify($account, $password)
    {
        if(!$account or !$password) return false;

        /* Get the user first. If $password length is 32, don't add the password condition.  */
        $record = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->beginIF(strlen($password) < 32)->andWhere('password')->eq(md5($password))->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch();

        /* If the length of $password is 32 or 40, checking by the auth hash. */
        $user = false;
        if($record)
        {
            $passwordLength = strlen($password);
            if($passwordLength < 32)
            {
                $user = $record;
            }
            elseif($passwordLength == 32)
            {
                $hash = $this->session->rand ? md5($record->password . $this->session->rand) : $record->password;
                $user = $password == $hash ? $record : '';
            }
            elseif($passwordLength == 40)
            {
                $hash = sha1($record->account . $record->password . $record->last);
                $user = $password == $hash ? $record : '';
            }
            if(!$user and md5($password) == $record->password) $user = $record;
        }

        if($user)
        {
            $ip   = $this->server->remote_addr;
            $last = $this->server->request_time;

            $user->lastTime       = $user->last;
            $user->last           = date(DT_DATETIME1, $last);
            $user->admin          = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $user->modifyPassword = ($user->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
            if($user->modifyPassword) $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
            if(!$user->modifyPassword and !empty($this->config->safe->changeWeak))
            {
                $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
                if($user->modifyPassword) $user->modifyPasswordReason = 'weak';
            }

            /* code for bug #2729. */
            if(defined('IN_USE')) $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();
        }
        return $user;
    }

    /**
     * Identify user by PHP_AUTH_USER.
     *
     * @access public
     * @return void
     */
    public function identifyByPhpAuth()
    {
        $account  = $this->server->php_auth_user;
        $password = $this->server->php_auth_pw;
        $user     = $this->identify($account, $password);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $user->groups = $this->getGroups($account);
        //$user->view   = $this->grantUserView($user->account, $user->rights['acls']);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('common')->loadConfigFromDB();
    }

    /**
     * Identify user by cookie.
     *
     * @access public
     * @return void
     */
    public function identifyByCookie()
    {
        $account  = $this->cookie->za;
        $authHash = $this->cookie->zp;
        $user     = $this->identify($account, $authHash);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $user->groups = $this->getGroups($account);
        //$user->view   = $this->grantUserView($user->account, $user->rights['acls']);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('common')->loadConfigFromDB();

        $this->keepLogin($user);
    }

    /**
     * Authorize a user.
     *
     * @param   string $account
     * @access  public
     * @return  array the user rights.
     */
    public function authorize($account)
    {
        $account = filter_var($account, FILTER_SANITIZE_STRING);
        if(!$account) return false;

        $rights = array();
        if($account == 'guest')
        {
            $acl  = $this->dao->select('acl')->from(TABLE_GROUP)->where('name')->eq('guest')->fetch('acl');
            $acls = empty($acl) ? array() : json_decode($acl, true);

            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.id = t2.`group`')->where('t1.name')->eq('guest');
        }
        else
        {
            $groups = $this->dao->select('t1.acl')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id=t2.`group`')
                ->where('t2.account')->eq($account)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t1.role')->ne('limited')
                ->fetchAll();

            /* Init variables. */
            $acls = array();

            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id = t2.`group`')
                ->leftJoin(TABLE_GROUPPRIV)->alias('t3')->on('t2.`group` = t3.`group`')
                ->where('t2.account')->eq($account)
                ->andWhere('t1.vision')->eq($this->config->vision);
        }

        $stmt = $sql->query();
        if(!$stmt) return array('rights' => $rights, 'acls' => $acls);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }

        /* Set basic priv when no any priv. */
        if(empty($rights))
        {
            $rights['index']['index'] = 1;
            $rights['my']['index']    = 1;
        }
        return array('rights' => $rights, 'acls' => $acls);
    }

    /**
     * Plus the fail times.
     *
     * @param  int    $account
     * @access public
     * @return void
     */
    public function failPlus($account)
    {
        /* Save session fails. */
        $sessionFails  = (int)$this->session->loginFails;
        $sessionFails += 1;
        $this->session->set('loginFails', $sessionFails);
        if($sessionFails >= $this->config->user->failTimes) $this->session->set("{$account}.loginLocked", date('Y-m-d H:i:s'));

        $user  = $this->dao->select('fails')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if(empty($user)) return 0;

        $fails = $user->fails;
        $fails ++;
        if($fails < $this->config->user->failTimes)
        {
            $locked    = '0000-00-00 00:00:00';
            $failTimes = $fails;
        }
        else
        {
            $locked    = date('Y-m-d H:i:s');
            $failTimes = 0;
        }
        $this->dao->update(TABLE_USER)->set('fails')->eq($failTimes)->set('locked')->eq($locked)->where('account')->eq($account)->exec();

        return $fails;
    }

    /**
     * Check whether the user is locked.
     *
     * @param  int    $account
     * @access public
     * @return void
     */
    public function checkLocked($account)
    {
        if($this->session->{"{$account}.loginLocked"} and (time() - strtotime($this->session->{"{$account}.loginLocked"})) <= $this->config->user->lockMinutes * 60) return true;

        $user = $this->dao->select('locked')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if(empty($user)) return false;

        if((time() - strtotime($user->locked)) > $this->config->user->lockMinutes * 60) return false;
        return true;
    }

    /**
     * Check Tmp dir.
     *
     * @access public
     * @return void
     */
    public function checkTmp()
    {
        if(!is_dir($this->app->tmpRoot))   mkdir($this->app->tmpRoot,   0755, true);
        if(!is_dir($this->app->cacheRoot)) mkdir($this->app->cacheRoot, 0755, true);
        if(!is_dir($this->app->logRoot))   mkdir($this->app->logRoot,   0755, true);
        if(!is_dir($this->app->logRoot))   return false;

        $file = $this->app->logRoot . DS . 'demo.txt';
        if($fp = @fopen($file, 'a+'))
        {
            @fclose($fp);
            @unlink($file);
        }
        else
        {
            return false;
        }
        return true;
    }

    /**
     * Get vision list.
     *
     * @access public
     * @return array
     */
    public function getVisionList()
    {
        $visionList = $this->lang->visionList;

        foreach($visionList as $visionKey => $visionName)
        {
            if(strpos($this->config->visions, ",{$visionKey},") === false) unset($visionList[$visionKey]);
        }

        return $visionList;
    }

    /**
     * Switch admin.
     *
     * @access public
     * @return void
     */
    public function su()
    {
        $company = $this->dao->select('admins')->from(TABLE_COMPANY)->fetch();
        $admins  = explode(',', trim($company->admins, ','));
        $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($admins[0])->fetch();
    }
}
