<?php
/**
 * The model file of action module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     action
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
class actionModel extends model
{
    const BE_UNDELETED  = 0;    // The deleted object has been undeleted.
    const CAN_UNDELETED = 1;    // The deleted object can be undeleted.
    const BE_HIDDEN     = 2;    // The deleted object has been hidded.

    /**
     * Create a action.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $comment
     * @param  string $extra        the extra info of this action, according to different modules and actions, can set different extra.
     * @param  string $actor
     * @param  bool   $autoDelete
     * @access public
     * @return int
     */
    public function create($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $autoDelete = true)
    {
        if(strtolower($actionType) == 'commented' and empty($comment)) return false;

        $actor      = $actor ? $actor : $this->app->user->account;
        $actionType = strtolower($actionType);
        $actor      = ($actionType == 'openedbysystem' or $actionType == 'closedbysystem') ? '' : $actor;
        if($actor == 'guest' and $actionType == 'logout') return false;

        $objectType = str_replace('`', '', $objectType);

        $action             = new stdclass();
        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->actor      = $actor;
        $action->action     = $actionType;
        $action->date       = helper::now();
        $action->extra      = $extra;
        if(!defined('IN_UPGRADE')) $action->vision = $this->config->vision;

        if($objectType == 'story' and strpos(',reviewpassed,reviewrejected,reviewclarified,', ",$actionType,") !== false) $action->actor = $this->lang->action->system;

        /* Use purifier to process comment. Fix bug #2683. */
        $action->comment = fixer::stripDataTags($comment);

        /* Process action. */
        if($this->post->uid)
        {
            $action = $this->loadModel('file')->processImgURL($action, 'comment', $this->post->uid);
            if($autoDelete) $this->file->autoDelete($this->post->uid);
        }

        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        $actionID = $this->dao->lastInsertID();

        if($this->post->uid) $this->file->updateObjectID($this->post->uid, $objectID, $objectType);

        /* Call the message notification function. */
        // $this->loadModel('message')->send($objectType, $objectID, $actionType, $actionID, $actor);

        /* Add index for global search. */
        $this->saveIndex($objectType, $objectID, $actionType);

        return $actionID;
    }

    /**
     * Get actions of an object.
     *
     * @param  int    $objectType
     * @param  int    $objectID
     * @param  string $order  default: 'date, id'
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($objectType, $objectID, $order = 'date, id', $pager = null)
    {
        $commiters = $this->loadModel('user')->getCommiters();
        $actions   = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq((int)$objectID)
            ->orderBy($order)
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');
        $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in(array_unique(array_column($actions, 'actor')))->fetchPairs('account', 'realname');
        foreach($actions as $action) $action->actorName = zget($users, $action->actor, $action->actor);

        //$histories = $this->getHistory(array_keys($actions));
        //$this->loadModel('file');


        //foreach($actions as $actionID => $action)
        //{
        //    $action->history = isset($histories[$actionID]) ? $histories[$actionID] : array();

        //    $action->comment = $this->file->setImgSize($action->comment, $this->config->action->commonImgSize);

        //    $actions[$actionID] = $action;
        //}

        return $actions;
    }

    /**
     * Get an action record.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function getById($actionID)
    {
        $action = $this->dao->findById((int)$actionID)->from(TABLE_ACTION)->fetch();

        /* Splice domain name for connection when the action is equal to 'repocreated'.*/
        if($action->action == 'repocreated') $action->extra = str_replace("href='", "href='" . common::getSysURL(), $action->extra);

        return $action;
    }

    /**
     * Get deleted objects.
     *
     * @param  string    $type all|hidden
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getTrashes($type, $orderBy, $pager)
    {
        $extra = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        $trashes = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq($extra)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        if(!$trashes) return array();

        /* Group trashes by objectType, and get there name field. */
        foreach($trashes as $object)
        {
            $object->objectType = str_replace('`', '', $object->objectType);
            $typeTrashes[$object->objectType][] = $object->objectID;
        }

        foreach($typeTrashes as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;
            if(!isset($this->config->action->objectNameFields[$objectType])) continue;

            $objectIdList = array_unique($objectIdList);
            $table        = $this->config->objectTables[$objectType];
            $field        = $this->config->action->objectNameFields[$objectType];
            if($objectType == 'pipeline')
            {
                $objectNames['jenkins'] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('jenkins')->fetchPairs();
                $objectNames['gitlab']  = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('gitlab')->fetchPairs();
            }
            else
            {
                $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
            }
        }

        /* Add name field to the trashes. */
        foreach($trashes as $trash)
        {
            $objectType = $trash->objectType;
            if($objectType == 'pipeline')
            {
                if(isset($objectNames['gitlab'][$trash->objectID]))  $objectType = 'gitlab';
                if(isset($objectNames['jenkins'][$trash->objectID])) $objectType = 'jenkins';
                $trash->objectType = $objectType;
            }

            $trash->objectName = isset($objectNames[$objectType][$trash->objectID]) ? $objectNames[$objectType][$trash->objectID] : '';
        }
        return $trashes;
    }

    /**
     * Get histories of an action.
     *
     * @param  int    $actionID
     * @access public
     * @return array
     */
    public function getHistory($actionID)
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->orderBy('id')->fetchGroup('action');
    }

    /**
     * Log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return void
     */
    public function logHistory($actionID, $changes)
    {
        if(empty($actionID)) return false;
        foreach($changes as $change)
        {
            if(is_object($change))
            {
                $change->action = $actionID;
            }
            else
            {
                $change['action'] = $actionID;
            }
            $this->dao->insert(TABLE_HISTORY)->data($change)->exec();
        }
    }

    /**
     * Print actions of an object.
     *
     * @param  object    $action
     * @param  string   $desc
     * @access public
     * @return void
     */
    public function printAction($action, $desc = '')
    {
        if(!isset($action->objectType) or !isset($action->action)) return false;

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         * Set the desc string of this action.
         *
         * 1. If the module of this action has defined desc of this actionType, use it.
         * 2. If no defined in the module language, search the common action define.
         * 3. If not found in the lang->action->desc, use the $lang->action->desc->common or $lang->action->desc->extra as the default.
         */
        if(empty($desc))
        {
            $desc = $action->extra ? $this->lang->action->desc->extra : $this->lang->action->desc->common;
        }

        if($this->app->getViewType() == 'mhtml') $action->date = date('m-d H:i', strtotime($action->date));

        /* Cycle actions, replace vars. */
        foreach($action as $key => $value)
        {
            if($key == 'history') continue;

            /* Desc can be an array or string. */
            if(is_array($desc))
            {
                if($key == 'extra') continue;
                if($action->objectType == 'story' and $action->action = 'reviewed' and strpos($action->extra, '|') !== false and $key == 'actor')
                {
                    $desc['main'] = str_replace('$actor', $this->lang->action->superReviewer . ' ' . $value, $desc['main']);
                }
                else
                {
                    $desc['main'] = str_replace('$' . $key, $value, $desc['main']);
                }
            }
            else
            {
                $desc = str_replace('$' . $key, $value, $desc);
            }
        }

        /* If the desc is an array, process extra. Please bug/lang. */
        if(is_array($desc))
        {
            $extra = strtolower($action->extra);

            /* Fix bug #741. */
            if(isset($desc['extra'])) $desc['extra'] = $this->lang->$objectType->{$desc['extra']};

            $actionDesc = '';
            if(isset($desc['extra'][$extra]))
            {
                $actionDesc = str_replace('$extra', $desc['extra'][$extra], $desc['main']);
            }
            else
            {
                $actionDesc = str_replace('$extra', $action->extra, $desc['main']);
            }

            if($action->objectType == 'story' and $action->action == 'reviewed')
            {
                if(strpos($action->extra, ',') !== false)
                {
                    list($extra, $reason) = explode(',', $extra);
                    $desc['reason'] = $this->lang->$objectType->{$desc['reason']};
                    $actionDesc = str_replace(array('$extra', '$reason'), array($desc['extra'][$extra], $desc['reason'][$reason]), $desc['main']);
                }

                if(strpos($action->extra, '|') !== false)
                {
                    list($extra, $isSuperReviewer) = explode('|', $extra);
                    $actionDesc = str_replace('$extra', $desc['extra'][$extra], $desc['main']);
                }
            }
            echo $actionDesc;
        }
        else
        {
            echo $desc;
        }
    }

    /**
     * Get actions as dynamic.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamic($account = 'all', $period = 'all', $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next')
    {
        /* Computer the begin and end date of a period. */
        $beginAndEnd = $this->computeBeginAndEnd($period);
        extract($beginAndEnd);

        /* Build has priv condition. */
        $condition = 1;

        //$this->loadModel('doc');
        //$libs = $this->doc->getLibs('includeDeleted') + array('' => '');
        //$docs = $this->doc->getPrivDocs(array_keys($libs), 0, 'all');

        $actionCondition = $this->getActionCondition();
        if(!$actionCondition and !$this->app->user->admin and isset($this->app->user->rights['acls']['actions'])) return array();

        /* Restrict query data in this year when no limit for big data. */
        $beginDate = '';
        if($period == 'all')
        {
            $year = date('Y');
            $beginDate = $year . '-01-01';

            /* When query all dynamic then query the data of the last two years at most. */
            if($this->app->getMethodName() == 'dynamic') $beginDate = $year - 1 . '-01-01';
        }

        /* Get actions. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where(1)
            ->beginIF($period != 'all')->andWhere('date')->gt($begin)->fi()
            ->beginIF($period != 'all')->andWhere('date')->lt($end)->fi()
            ->beginIF($date)->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")->fi()
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF($beginDate)->andWhere('date')->ge($beginDate)->fi()
            /* Filter out client login/logout actions. */
            ->andWhere('action')->notin('disconnectxuanxuan,loginxuanxuan')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        if(!$actions) return array();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');
        return $this->transformActions($actions);
    }

    /**
     * Get dynamic show action.
     *
     * @return String
     */
    public function getActionCondition()
    {
        if($this->app->user->admin) return '';

        $actionCondition = '';
        if(isset($this->app->user->rights['acls']['actions']))
        {
            if(empty($this->app->user->rights['acls']['actions'])) return '';

            foreach($this->app->user->rights['acls']['actions'] as $moduleName => $actions)
            {
                $actionCondition .= "(`objectType` = '$moduleName' and `action` " . helper::dbIN($actions) . ") or ";
            }
            $actionCondition = trim($actionCondition, 'or ');
        }
        return $actionCondition;
    }

    /**
     * Get dynamic by search.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamicBySearch($queryID, $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next')
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
        if($query)
        {
            $this->session->set('actionQuery', $query->sql);
            $this->session->set('actionForm', $query->form);
        }
        if($this->session->actionQuery == false) $this->session->set('actionQuery', ' 1 = 1');

        $actionQuery   = $this->session->actionQuery;



        /* If the sql not include 'project', add check purview for project. */
        if(strpos($actionQuery, $allProjects) !== false)
        {
            $actionQuery = str_replace($allProjects, '1', $actionQuery);
        }

        /* If the sql not include 'execution', add check purview for execution. */
        if(strpos($actionQuery, $allExecutions) !== false)
        {
            $actionQuery = str_replace($allExecutions, '1', $actionQuery);
        }


        if($date) $actionQuery = "($actionQuery) AND " . ('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'");

        /* If this vision is lite, delete product actions. */
        if($this->config->vision == 'lite') $actionQuery .= " AND objectType != 'product'";

        $actionQuery .= " AND vision = '" . $this->config->vision . "'";
        $actions      = $this->getBySQL($actionQuery, $orderBy, $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');
        if(!$actions) return array();
        return $this->transformActions($actions);
    }

    /**
     * Get actions by SQL.
     *
     * @param  string $sql
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySQL($sql, $orderBy, $pager = null)
    {
        $actionCondition = $this->getActionCondition();
        if(is_array($actionCondition)) return array();

        return $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where($sql)
            ->beginIF(!empty($actionCondition))->andWhere("($actionCondition)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Transform the actions for display.
     *
     * @param  array    $actions
     * @access public
     * @return object
     */
    public function transformActions($actions)
    {
        $this->app->loadLang('todo');
        $this->app->loadLang('stakeholder');
        $this->app->loadLang('branch');

        /* Get commiters and the same department users. */
        $commiters = $this->loadModel('user')->getCommiters();
        //$deptUsers = isset($this->app->user->dept) ? $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id') : '';

        /* Get object names, object projects and requirements by actions. */
        $relatedData  = $this->getRelatedDataByActions($actions);
        $objectNames  = $relatedData['objectNames'];

        foreach($actions as $i => $action)
        {
            /* Add name field to the actions. */
            $action->objectName = isset($objectNames[$action->objectType][$action->objectID]) ? $objectNames[$action->objectType][$action->objectID] : '';

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);

            $action->originalDate = $action->date;
            $action->date         = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel  = isset($this->lang->$objectType->$actionType) ? $this->lang->$objectType->$actionType : $action->action;
            $action->actionLabel  = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->actionLabel;
            $action->objectLabel  = $this->getObjectLabel($objectType, $action->objectID, $actionType);

            /* Other actions, create a link. */
            if(!$this->setObjectLink($action))
            {
                unset($actions[$i]);
                continue;
            }

            if(isset($this->lang->action->skipFields->$actionType))
            {
                foreach($this->lang->action->skipFields->$actionType as $field) $action->$field = '';
            }

            $action->major = (isset($this->config->action->majorList[$action->objectType]) && in_array($action->action, $this->config->action->majorList[$action->objectType])) ? 1 : 0;
        }
        return $actions;
    }

    /**
     * Get related data by actions.
     *
     * @param  array    $actions
     * @access public
     * @return array
     */
    public function getRelatedDataByActions($actions)
    {
        $objectNames     = array();

        foreach($actions as $object) $objectTypes[$object->objectType][$object->objectID] = $object->objectID;
        foreach($objectTypes as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType]) and $objectType != 'makeup') continue;    // If no defination for this type, omit it.

            $table = $this->config->objectTables[$objectType];
            $field = zget($this->config->action->objectNameFields, $objectType, '');
            if(empty($field)) continue;

            if($table)
            {
                $objectName = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();

                $objectNames[$objectType] = $objectName;
            }
        }
        $objectNames['user'][0] = 'guest';    // Add guest account.

        $relatedData['objectNames']  = $objectNames;
        return $relatedData;
    }

    /**
     * Get object label.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return string
     */
    public function getObjectLabel($objectType, $objectID, $actionType)
    {
        $actionObjectLabel = $objectType;
        if(isset($this->lang->action->label->$objectType))
        {
            $objectLabel = $this->lang->action->label->$objectType;

            if(!is_array($objectLabel)) $actionObjectLabel = $objectLabel;
            if(is_array($objectLabel) and isset($objectLabel[$actionType])) $actionObjectLabel = $objectLabel[$actionType];
        }

        return $actionObjectLabel;
    }

    /**
     * Set objectLink
     *
     * @param  object   $action
     * @access public
     * @return object|bool
     */
    public function setObjectLink($action)
    {
        $action->objectLink  = '';
        $action->objectLabel = zget($this->lang->action->objectTypes, $action->objectLabel);
        if(strpos($action->objectLabel, '|') !== false)
        {
            list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
            $params = sprintf($vars, $action->objectID);
            $action->objectLink = helper::createLink($moduleName, $methodName, $params);
            $action->objectLabel = $objectLabel;
        }

        return $action;
    }

    /**
     * Compute the begin date and end date of a period.
     *
     * @param  string    $period   all|today|yesterday|twodaysago|latest2days|thisweek|lastweek|thismonth|lastmonth
     * @access public
     * @return array
     */
    public function computeBeginAndEnd($period)
    {
        $this->app->loadClass('date');

        $today      = date('Y-m-d');
        $tomorrow   = date::tomorrow();
        $yesterday  = date::yesterday();
        $twoDaysAgo = date::twoDaysAgo();

        $period = strtolower($period);

        if($period == 'all')        return array('begin' => '1970-1-1',  'end' => '2109-1-1');
        if($period == 'today')      return array('begin' => $today,      'end' => $tomorrow);
        if($period == 'yesterday')  return array('begin' => $yesterday,  'end' => $today);
        if($period == 'twodaysago') return array('begin' => $twoDaysAgo, 'end' => $yesterday);
        if($period == 'latest3days')return array('begin' => $twoDaysAgo, 'end' => $tomorrow);

        /* If the period is by week, add the end time to the end date. */
        if($period == 'thisweek' or $period == 'lastweek')
        {
            $func = "get$period";
            extract(date::$func());
            return array('begin' => $begin, 'end' => $end . ' 23:59:59');
        }

        if($period == 'thismonth')  return date::getThisMonth();
        if($period == 'lastmonth')  return date::getLastMonth();
    }

    /**
     * Print changes of every action.
     *
     * @param  string    $objectType
     * @param  array     $histories
     * @param  bool      $canChangeTag
     * @access public
     * @return void
     */
    public function printChanges($objectType, $histories, $canChangeTag = true)
    {
        if(empty($histories)) return;

        $maxLength            = 0;          // The max length of fields names.
        $historiesWithDiff    = array();    // To save histories without diff info.
        $historiesWithoutDiff = array();    // To save histories with diff info.

        /* Diff histories by hasing diff info or not. Thus we can to make sure the field with diff show at last. */
        foreach($histories as $history)
        {
            $fieldName = $history->field;
            $history->fieldLabel = (isset($this->lang->$objectType) && isset($this->lang->$objectType->$fieldName)) ? $this->lang->$objectType->$fieldName : $fieldName;
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
            $history->diff ? $historiesWithDiff[] = $history : $historiesWithoutDiff[] = $history;
        }
        $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                $history->diff      = str_replace(array('<ins>', '</ins>', '<del>', '</del>'), array('[ins]', '[/ins]', '[del]', '[/del]'), $history->diff);
                $history->diff      = ($history->field != 'subversion' and $history->field != 'git') ? htmlSpecialString($history->diff) : $history->diff;   // Keep the diff link.
                $history->diff      = str_replace(array('[ins]', '[/ins]', '[del]', '[/del]'), array('<ins>', '</ins>', '<del>', '</del>'), $history->diff);
                $history->diff      = nl2br($history->diff);
                $history->noTagDiff = $canChangeTag ? preg_replace('/&lt;\/?([a-z][a-z0-9]*)[^\/]*\/?&gt;/Ui', '', $history->diff) : '';
                printf($this->lang->action->desc->diff2, $history->fieldLabel, $history->noTagDiff, $history->diff);
            }
            else
            {
                printf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
    }

    /**
     * Undelete a record.
     *
     * @param  int      $actionID
     * @access public
     * @return void
     */
    public function undelete($actionID)
    {
        $action = $this->getById($actionID);
        if($action->action != 'deleted') return;

        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'undeleted');
    }

    /**
     * Hide an object.
     *
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function hideOne($actionID)
    {
        $action = $this->getById($actionID);
        if($action->action != 'deleted') return;

        $this->dao->update(TABLE_ACTION)->set('extra')->eq(self::BE_HIDDEN)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'hidden');
    }

    /**
     * Hide all deleted objects.
     *
     * @access public
     * @return void
     */
    public function hideAll()
    {
        $this->dao->update(TABLE_ACTION)
            ->set('extra')->eq(self::BE_HIDDEN)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq(self::CAN_UNDELETED)
            ->exec();
    }

    /**
     * Update comment of a action.
     *
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function updateComment($actionID)
    {
        $action = $this->getById($actionID);
        $action->comment = trim(strip_tags($this->post->lastComment, $this->config->allowedTags));

        /* Process action. */
        $action = $this->loadModel('file')->processImgURL($action, 'comment', $this->post->uid);

        $this->dao->update(TABLE_ACTION)
            ->set('date')->eq(helper::now())
            ->set('comment')->eq($action->comment)
            ->where('id')->eq($actionID)
            ->exec();
        $this->file->updateObjectID($this->post->uid, $action->objectID, $action->objectType);
    }

    /**
     * Build date group by actions
     *
     * @param  array  $actions
     * @param  string $direction
     * @param  string $type all|today|yesterday|thisweek|lastweek|thismonth|lastmonth
     * @param  string $orderBy date_desc|date_asc
     * @access public
     * @return array
     */
    public function buildDateGroup($actions, $direction = 'next', $type = 'today', $orderBy = 'date_desc')
    {
        $dateGroup = array();
        foreach($actions as $action)
        {
            $timeStamp    = strtotime(isset($action->originalDate) ? $action->originalDate : $action->date);
            $date         = $type == 'all' ? date(DT_DATE3, $timeStamp) : date(DT_DATE4, $timeStamp);
            $action->time = date(DT_TIME2, $timeStamp);
            $dateGroup[$date][] = $action;
        }

        if($dateGroup)
        {
            $lastDateActions = $this->dao->select('*')->from(TABLE_ACTION)->where($this->session->actionQueryCondition)->andWhere("(LEFT(`date`, 10) = '" . substr($action->originalDate, 0, 10) . "')")->orderBy($this->session->actionOrderBy)->fetchAll('id');
            if(count($dateGroup[$date]) < count($lastDateActions))
            {
                unset($dateGroup[$date]);
                $lastDateActions = $this->transformActions($lastDateActions);
                foreach($lastDateActions as $action)
                {
                    $timeStamp    = strtotime(isset($action->originalDate) ? $action->originalDate : $action->date);
                    $date         = $type == 'all' ? date(DT_DATE3, $timeStamp) : date(DT_DATE4, $timeStamp);
                    $action->time = date(DT_TIME2, $timeStamp);
                    $dateGroup[$date][] = $action;
                }
            }
        }

        /* Modify date to the corrret order. */
        if($this->app->rawModule != 'company' and $direction != 'next')
        {
            $dateGroup = array_reverse($dateGroup);
        }
        elseif($this->app->rawModule == 'company')
        {
            if($direction == 'pre') $dateGroup = array_reverse($dateGroup);
            if(($direction == 'next' and $orderBy == 'date_asc') or ($direction == 'pre' and $orderBy == 'date_desc'))
            {
                foreach($dateGroup as $key => $dateItem) $dateGroup[$key] = array_reverse($dateItem);
            }
        }
        return $dateGroup;
    }

    /**
     * Check Has pre or next.
     *
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return bool
     */
    public function hasPreOrNext($date, $direction = 'next')
    {
        $condition = $this->session->actionQueryCondition;

        /* Remove date condition for direction. */
        $condition = preg_replace("/AND +date[\<\>]'\d{4}\-\d{2}\-\d{2}'/", '', $condition);
        $count     = $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where($condition)
            ->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")
            ->fetch('count');
        return $count > 0;
    }

    /**
     * Save global search object index information.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return bool
     */
    public function saveIndex($objectType, $objectID, $actionType)
    {
        $this->loadModel('search');
        $actionType = strtolower($actionType);
        if(!isset($this->config->search->fields->$objectType)) return true;
        if(strpos($this->config->search->buildAction, ",{$actionType},") === false and empty($_POST['comment'])) return true;
        if($actionType == 'deleted' or $actionType == 'erased') return $this->search->deleteIndex($objectType, $objectID);

        $field = $this->config->search->fields->$objectType;
        $query = $this->search->buildIndexQuery($objectType, $testDeleted = false);
        $data  = $query->andWhere('t1.' . $field->id)->eq($objectID)->fetch();
        if(empty($data)) return true;

        $data->comment = '';
        if($objectType == 'effort' and $data->objectType == 'task') return true;
        if($objectType == 'case')
        {
            $caseStep     = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($objectID)->andWhere('version')->eq($data->version)->fetchAll();
            $data->desc   = '';
            $data->expect = '';
            foreach($caseStep as $step)
            {
                $data->desc   .= $step->desc . "\n";
                $data->expect .= $step->expect . "\n";
            }
        }

        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id asc')
            ->fetchAll();
        foreach($actions as $action)
        {
            if($action->action == 'opened') $data->{$field->addedDate} = $action->date;
            $data->{$field->editedDate} = $action->date;
            if(!empty($action->comment)) $data->comment .= $action->comment . "\n";
        }

        $this->search->saveIndex($objectType, $data);
    }

    /**
     * Print actions of an object for API(JIHU).
     *
     * @param  object    $action
     * @access public
     * @return void
     */
    public function printActionForGitLab($action)
    {
        if(!isset($action->objectType) or !isset($action->action)) return false;

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        if(isset($this->lang->action->apiTitle->$actionType) and isset($action->extra))
        {
            /* If extra column is a username, then assemble link to that. */
            if($action->action == "assigned")
            {
                $userDetails = $this->loadModel('user')->getUserDetailsForAPI($action->extra);
                if(isset($userDetails[$action->extra]))
                {
                    $userDetail    = $userDetails[$action->extra];
                    $action->extra = "<a href='{$userDetail->url}' target='_blank'>{$action->extra}</a>";
                }
            }

            echo sprintf($this->lang->action->apiTitle->$actionType, $action->extra);
        }
        elseif(isset($this->lang->action->apiTitle->$actionType) and !isset($action->extra))
        {
            echo $this->lang->action->apiTitle->$actionType;
        }
        else
        {
            echo $actionType;
        }
    }

    /**
     * Process action for API.
     *
     * @param  array  $actions
     * @param  array  $users
     * @param  array  $objectLang
     * @access public
     * @return array
     */
    public function processActionForAPI($actions, $users = array(), $objectLang = array())
    {
        $actions = (array)$actions;
        foreach($actions as $action)
        {
            $action->actor = zget($users, $action->actor);
            if($action->action == 'assigned') $action->extra = zget($users, $action->extra);
            if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            ob_start();
            $this->printAction($action);
            $action->desc = ob_get_contents();
            ob_end_clean();

            if($action->history)
            {
                foreach($action->history as $i => $history)
                {
                    $history->fieldName = zget($objectLang, $history->field);
                    $action->history[$i] = $history;
                }
            }
        }
        return array_values($actions);
    }

    /**
     * Process dynamic for API.
     *
     * @param  array    $dynamics
     * @access public
     * @return array
     */
    public function processDynamicForAPI($dynamics)
    {
        $users = $this->loadModel('user')->getList();
        $simplifyUsers = array();
        foreach($users as $user)
        {
            $simplifyUser = new stdclass();
            $simplifyUser->id       = $user->id;
            $simplifyUser->account  = $user->account;
            $simplifyUser->realname = $user->realname;
            $simplifyUser->avatar   = $user->avatar;
            $simplifyUsers[$user->account] = $simplifyUser;
        }

        $actions = array();
        foreach($dynamics as $key => $dynamic)
        {
            if($dynamic->objectType == 'user') continue;

            $simplifyUser = zget($simplifyUsers, $dynamic->actor, '');
            $actor = $simplifyUser;
            if(empty($simplifyUser))
            {
                $actor = new stdclass();
                $actor->id       = 0;
                $actor->account  = $dynamic->actor;
                $actor->realname = $dynamic->actor;
                $actor->avatar   = '';
            }

            $dynamic->actor = $actor;
            $actions[]      = $dynamic;
        }

        return $actions;
    }
}
