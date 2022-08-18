<?php
/**
 * The control file of user module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     user
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class user extends control
{
    public $referer;

    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('company')->setMenu();
        $this->app->loadModuleConfig($this->moduleName);//Finish task #5118.(Fix bug #2271)
    }

    /**
     * View a user.
     *
     * @param  string $userID
     * @access public
     * @return void
     */
    public function view($userID)
    {
        $userID = (int)$userID;
        $this->locate($this->createLink('user', 'todo', "userID=$userID&type=all"));
    }

    /**
     * The profile of a user.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function profile($userID = '')
    {
        if(empty($userID)) $userID = $this->app->user->id;

        $user    = $this->user->getById($userID, 'id');
        $account = $user->account;
        $users   = $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id');

        $this->view->title        = "USER #$user->id $user->account/" . $this->lang->user->profile;
        $this->view->position[]   = $this->lang->user->common;
        $this->view->position[]   = $this->lang->user->profile;
        $this->view->user         = $user;
        $this->view->groups       = $this->loadModel('group')->getByAccount($account);
        $this->view->deptPath     = $this->dept->getParents($user->dept);
        $this->view->personalData = $this->user->getPersonalData($user->account);
        $this->view->userList     = $this->user->setUserList($users, $userID);

        $this->display();
    }

    /**
     * Set the rerferer.
     *
     * @param  string   $referer
     * @access public
     * @return void
     */
    public function setReferer($referer = '')
    {
        $this->referer = $this->server->http_referer ? $this->server->http_referer: '';
        if(!empty($referer)) $this->referer = helper::safe64Decode($referer);

        /* Build link regular. */
        $webRoot = $this->config->webRoot;
        $linkReg = $webRoot . 'index.php?' . $this->config->moduleVar . '=\w+&' . $this->config->methodVar . '=\w+';
        if($this->config->requestType == 'PATH_INFO') $linkReg = $webRoot . '\w+' . $this->config->requestFix . '\w+';
        $linkReg = str_replace(array('/', '.', '?', '-'), array('\/', '\.', '\?', '\-'), $linkReg);

        /* Check link by regular. */
        $this->referer = preg_match('/^' . $linkReg . '/', $this->referer) ? $this->referer : $webRoot;
    }

    /**
     * Create a suer.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function create($deptID = 0)
    {
        //$this->lang->user->menu      = $this->lang->company->menu;
        //$this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            if(strtolower($_POST['account']) == 'guest')
            {
                return $this->send(array('result' => 'fail', 'message' => str_replace('ID ', '', sprintf($this->lang->user->error->reserved, $_POST['account']))));
            }

            $userID = $this->user->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $userID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('my', 'index')));
        }

        $groups = $this->dao->select('id, name, role')
            ->from(TABLE_GROUP)
            ->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[] = $this->lang->user->create;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->depts     = array();//$this->dept->getOptionMenu();
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;
        $this->view->deptID    = $deptID;
        $this->view->rand      = $this->user->updateSessionRandom();
        $this->view->companies = $this->loadModel('company')->getOutsideCompanies();

        $this->display();
    }

    /**
     * Batch create users.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function batchCreate($deptID = 0)
    {
        $groups = $this->dao->select('id, name, role')
            ->from(TABLE_GROUP)
            ->where('vision')->eq($this->config->vision)
            ->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $userIDList = $this->user->batchCreate();

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $userIDList));
            return print(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        /* Set custom. */
        foreach(explode(',', $this->config->user->availableBatchCreateFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $customFields[$field] = $this->lang->user->$field;
        }
        foreach(explode(',', $this->config->user->custom->batchCreateFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $showFields[$field] = $field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = join(',', $showFields);

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchCreate;
        $position[] = $this->lang->user->batchCreate;
        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->deptID     = $deptID;
        $this->view->groupList  = $groupList;
        $this->view->roleGroup  = $roleGroup;
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $this->user->getVisionList();

        $this->display();
    }

    /**
     * Edit a user.
     *
     * @param  string|int $userID   the int user id or account
     * @access public
     * @return void
     */
    public function edit($userID)
    {
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $user       = $this->user->getById($userID, 'id');
        $userGroups = $this->loadModel('group')->getByAccount($user->account);

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->edit;
        $position[] = $this->lang->user->edit;
        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->user       = $user;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->companies  = $this->loadModel('company')->getOutsideCompanies();
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->fetchPairs('id', 'name');
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $this->user->getVisionList();

        $this->display();
    }

    /**
     * Batch edit user.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function batchEdit($deptID = 0)
    {
        if(isset($_POST['users']))
        {
            $this->view->users = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($this->post->users)->orderBy('id')->fetchAll('id');
        }
        elseif($_POST)
        {
            if($this->post->account) $this->user->batchEdit();
            return print(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID=$deptID"), 'parent'));
        }
        else
        {
            return print(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID=$deptID"), 'parent'));
        }

        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        /* Set custom. */
        foreach(explode(',', $this->config->user->availableBatchEditFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $customFields[$field] = $this->lang->user->$field;
        }
        foreach(explode(',', $this->config->user->custom->batchEditFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $showFields[$field] = $field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = join(',', $showFields);

        $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchEdit;
        $this->view->position[] = $this->lang->user->batchEdit;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $this->user->getVisionList();

        $this->display();
    }

    /**
     * Delete a user.
     *
     * @param  int    $userID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($userID)
    {
        $user = $this->user->getByID($userID, 'id');
        if($this->app->user->admin and $this->app->user->account == $user->account) return;
        if($_POST)
        {
            if($this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) return print(js::alert($this->lang->user->error->verifyPassword));
            $this->user->delete(TABLE_USER, $userID);
            if(!dao::isError())
            {
                $this->loadModel('mail');
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('delete', $user->email);
            }

            /* if ajax request, send result. */
            if($this->server->ajax or $this->viewType == 'json')
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }
            return print(js::locate($this->session->userList, 'parent.parent'));
        }

        $this->view->rand = $this->user->updateSessionRandom();
        $this->view->user = $user;
        $this->display();
    }

    /**
     * Unlock a user.
     *
     * @param  int    $userID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlock($userID, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->user->confirmUnlock, $this->createLink('user', 'unlock', "userID=$userID&confirm=yes")));

        $user = $this->user->getById($userID, 'id');
        $this->user->cleanLocked($user->account);
        return print(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse'), 'parent'));
    }

    /**
     * User login, identify him and authorize him.
     *
     * @param string $referer
     * @param string $from
     *
     * @access public
     * @return void
     */
    public function login($referer = '', $from = '')
    {
        /* Check if you can operating on the folder. */
        $canModifyDIR = true;
        if($this->user->checkTmp() === false)
        {
            $canModifyDIR = false;
            $floderPath   = $this->app->tmpRoot;
        }
        elseif(!is_dir($this->app->dataRoot) or intval(substr(base_convert(@fileperms($this->app->dataRoot),10,8),-4)) < 755)
        {
            $canModifyDIR = false;
            $floderPath   = $this->app->dataRoot;
        }

        if(!$canModifyDIR)
        {
            if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                return print(sprintf($this->lang->user->mkdirWin, $floderPath, $floderPath));
            }
            else
            {
                return print(sprintf($this->lang->user->mkdirLinux, $floderPath, $floderPath, $floderPath, $floderPath));
            }
        }

        /* If not set admin, to init admin.  */
        if(!$this->loadModel('company')->getAdmin()) return print(js::locate(helper::createLink('admin', 'init')));

        $this->setReferer($referer);

        $loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');

        /* Reload lang by lang of get when viewType is json. */
        if($this->app->getViewType() == 'json' and $this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('user');
        }

        /* If user is logon, back to the rerferer. */
        if($this->user->isLogon())
        {
            if($this->app->getViewType() == 'json')
            {
                $data = $this->user->getDataInJSON($this->app->user);
                return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
            }

            $response['result']  = 'success';
            if(strpos($this->referer, $loginLink) === false and
               strpos($this->referer, $denyLink)  === false and
               strpos($this->referer, 'block')  === false and $this->referer
            )
            {
                $response['locate']  = $this->referer;
                return $this->send($response);
            }
            else
            {
                $response['locate']  = $this->config->webRoot;
                return $this->send($response);
            }
        }

        /* Passed account and password by post or get. */
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;

            $account = trim($account);
            if($this->user->checkLocked($account))
            {
                $response['result']  = 'fail';
                $response['message'] = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
                if($this->app->getViewType() == 'json') return print(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $failReason))));
                return $this->send($response);
            }

            if((!empty($this->config->safe->loginCaptcha) and strtolower($this->post->captcha) != strtolower($this->session->captcha) and $this->app->getViewType() != 'json'))
            {
                $response['result']  = 'fail';
                $response['message'] = $this->lang->user->errorCaptcha;
                return $this->send($response);
            }

            $user = $this->user->identify($account, $password);

            if($user)
            {
                /* Set user group, rights, view and aword login score. */
                $user = $this->user->login($user);

                /* Go to the referer. */
                if($this->post->referer and strpos($this->post->referer, $loginLink) === false and strpos($this->post->referer, $denyLink) === false and strpos($this->post->referer, 'block') === false)
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }

                    /* Get the module and method of the referer. */
                    if($this->config->requestType == 'PATH_INFO')
                    {
                        $path = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
                        $path = rtrim($path, '.html');
                        if(empty($path)) $path = $this->config->requestFix;
                        list($module, $method) = explode($this->config->requestFix, $path);
                    }
                    else
                    {
                        $url   = html_entity_decode($this->post->referer);
                        $param = substr($url, strrpos($url, '?') + 1);

                        $module = $this->config->default->module;
                        $method = $this->config->default->method;
                        if(strpos($param, '&') !== false) list($module, $method) = explode('&', $param);
                        $module = str_replace('m=', '', $module);
                        $method = str_replace('f=', '', $method);
                    }

                    $response['result'] = 'success';
                    if(common::hasPriv($module, $method))
                    {
                        $response['locate'] = $this->post->referer;
                        return $this->send($response);
                    }
                    else
                    {
                        $response['locate'] = helper::createLink('my', 'index');//$this->config->webRoot;
                        return $this->send($response);
                    }
                }
                else
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }

                    $response['locate'] = $this->config->webRoot;
                    $response['result'] = 'success';
                    return $this->send($response);
                }
            }
            else
            {
                $response['result']  = 'fail';
                $fails = $this->user->failPlus($account);
                if($this->app->getViewType() == 'json') return print(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $this->lang->user->loginFailed))));
                $remainTimes = $this->config->user->failTimes - $fails;
                if($remainTimes <= 0)
                {
                    $response['message'] = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
                    return $this->send($response);
                }
                else if($remainTimes <= 3)
                {
                    $response['message'] = sprintf($this->lang->user->lockWarning, $remainTimes);
                    return $this->send($response);
                }

                $response['message'] = $this->lang->user->loginFailed;
                return $this->send($response);
            }
        }
        else
        {
            $this->view->title     = $this->lang->user->login;
            $this->view->referer   = $this->referer;
            $this->view->s         = zget($this->config->global, 'sn', '');
            $this->view->keepLogin = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';
            $this->view->rand      = $this->user->updateSessionRandom();
            $this->display();
        }
    }

    /**
     * Deny page.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $refererBeforeDeny    the referer of the denied page.
     * @access public
     * @return void
     */
    public function deny($module, $method, $refererBeforeDeny = '')
    {
        $this->setReferer();
        $this->view->title             = $this->lang->user->deny;
        $this->view->module            = $module;
        $this->view->method            = $method;
        $this->view->denyPage          = $this->referer;        // The denied page.
        $this->view->refererBeforeDeny = $refererBeforeDeny;    // The referer of the denied page.
        $this->app->loadLang($module);
        $this->app->loadLang('my');

        /* Check deny type. */
        $rights  = $this->app->user->rights['rights'];
        $acls    = $this->app->user->rights['acls'];

        $module  = strtolower($module);
        $method  = strtolower($method);

        $denyType = 'nopriv';
        if(isset($rights[$module][$method]))
        {
            $menu = isset($lang->navGroup->$module) ? $lang->navGroup->$module : $module;
            $menu = strtolower($menu);

            if(!isset($acls['views'][$menu])) $denyType = 'noview';
            $this->view->menu = $menu;
        }

        $this->view->denyType = $denyType;

        $this->display();
    }

    /**
     * Logout.
     *
     * @access public
     * @return void
     */
    public function logout($referer = 0)
    {
        if(isset($this->app->user->id)) $this->loadModel('action')->create('user', $this->app->user->id, 'logout');
        session_destroy();
        setcookie('za', false);
        setcookie('zp', false);

        if($this->app->getViewType() == 'json') return print(json_encode(array('status' => 'success')));
        $vars = !empty($referer) ? "referer=$referer" : '';
        $this->locate($this->createLink('user', 'login', $vars));
    }

    /**
     * Reset password.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        if(!isset($_SESSION['resetFileName']))
        {
            $resetFileName = $this->app->getBasePath() . 'tmp' . DIRECTORY_SEPARATOR . uniqid('reset_') . '.txt';
            $this->session->set('resetFileName', $resetFileName);
        }

        $resetFileName = $this->session->resetFileName;

        $needCreateFile = false;
        if(!file_exists($resetFileName) or (time() - filemtime($resetFileName)) > 60 * 2) $needCreateFile = true;

        if($_POST)
        {
            if($needCreateFile) return print(js::reload('parent'));

            $result = $this->user->resetPassword();
            if(dao::isError()) return print(js::error(dao::getError()));
            if(!$result) return print(js::alert($this->lang->user->resetFail));

            echo js::alert($this->lang->user->resetSuccess);
            $referer = helper::safe64Encode($this->createLink('index', 'index'));
            return print(js::locate(inlink('logout', 'referer=' . $referer), 'parent'));
        }

        /* Remove the real path for security reason. */
        $pathPos       = strrpos($this->app->getBasePath(), DIRECTORY_SEPARATOR, -2);
        $resetFileName = substr($resetFileName, $pathPos+1);

        $this->view->title          = $this->lang->user->resetPassword;
        $this->view->status         = 'reset';
        $this->view->needCreateFile = $needCreateFile;
        $this->view->resetFileName  = $resetFileName;

        $this->display();
    }


    /**
     * crop avatar
     *
     * @param  int    $image
     * @access public
     * @return void
     */
    public function cropAvatar($image)
    {
        $image = $this->loadModel('file')->getByID($image);

        if(!empty($_POST))
        {
            $size = fixer::input('post')->get();
            $this->file->cropImage($image->realPath, $image->realPath, $size->left, $size->top, $size->right - $size->left, $size->bottom - $size->top, $size->scaled ? $size->scaleWidth : 0, $size->scaled ? $size->scaleHeight : 0);

            $this->app->user->avatar = $image->webPath;
            $this->session->set('user', $this->app->user);
            $this->dao->update(TABLE_USER)->set('avatar')->eq($image->webPath)->where('account')->eq($this->app->user->account)->exec();
            exit('success');
        }

        $this->view->user  = $this->user->getById($this->app->user->account);
        $this->view->title = $this->lang->user->cropAvatar;
        $this->view->image = $image;
        $this->display();
    }

    /**
     * Get user for ajax
     *
     * @param  string $requestID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function ajaxGetUser($taskID = '', $assignedTo = '')
    {
        $users = $this->user->getPairs('noletter, noclosed');
        $html = "<form method='post' target='hiddenwin' action='" . $this->createLink('task', 'assignedTo', "taskID=$taskID&assignedTo=$assignedTo") . "'>";
        $html .= html::select('assignedTo', $users, $assignedTo);
        $html .= html::submitButton('', '', 'btn btn-primary');
        $html .= '</form>';
        echo $html;
    }

    /**
     * Ajax get contact list.
     *
     * @param  string $dropdownName mailto|whitelist
     * @access public
     * @return string
     */
    public function ajaxGetContactList($dropdownName = 'mailto')
    {
        $contactList = $this->user->getContactLists($this->app->user->account, 'withnote');
        if(empty($contactList)) return false;
        return print(html::select('', $contactList, '', "class='form-control' onchange=\"setMailto('$dropdownName', this.value)\""));
    }

    /**
     * Ajax get group by vision.
     *
     * @param  string  $visions rnd|lite
     * @param  int     $i
     * @param  string  $selected
     * @access public
     * @return string
     */
    public function ajaxGetGroup($visions, $i = 0, $selected = '')
    {
        $visions   = explode(',', $visions);
        $groupList = $this->user->getGroupsByVisions($visions);
        if($i)
        {
            if($i > 1) $groupList = $groupList + array('ditto' => $this->lang->user->ditto);
            return print(html::select("group[$i][]", $groupList, $selected, 'size=3 multiple=multiple class="form-control chosen"'));
        }
        return print(html::select('group[]', $groupList, $selected, 'size=3 multiple=multiple class="form-control chosen"'));
    }

    /**
     * Refresh random for login
     *
     * @access public
     * @return void
     */
    public function refreshRandom()
    {
        $rand = (string)$this->user->updateSessionRandom();
        echo $rand;
    }
}
