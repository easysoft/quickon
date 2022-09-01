<?php
/**
 * The control file of backup of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     backup
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class backup extends control
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->backupPath = $this->backup->getBackupPath();
        if(!is_dir($this->backupPath))
        {
            if(!mkdir($this->backupPath, 0755, true)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
        }
        else
        {
            if(!is_writable($this->backupPath)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
        }
        if(!is_writable($this->app->getTmpRoot())) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->app->getTmpRoot());

        $this->loadModel('action');
        $this->loadModel('setting');
    }

    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $backups = array();
        if(empty($this->view->error))
        {
            $sqlFiles = glob("{$this->backupPath}*.sql*");
            if(!empty($sqlFiles))
            {
                foreach($sqlFiles as $file)
                {
                    $fileName   = basename($file);
                    $backupFile = new stdclass();
                    $backupFile->time  = filemtime($file);
                    $backupFile->name  = substr($fileName, 0, strpos($fileName, '.'));
                    $backupFile->sqlSummary   = $this->backup->getSQLSummary($file);
                    $backupFile->files[$file] = $this->backup->getBackupSummary($file);

                    $fileBackup = $this->backup->getBackupFile($backupFile->name, 'file');
                    if($fileBackup) $backupFile->files[$fileBackup] = $this->backup->getBackupSummary($fileBackup);

                    $codeBackup = $this->backup->getBackupFile($backupFile->name, 'code');
                    if($codeBackup) $backupFile->files[$codeBackup] = $this->backup->getBackupSummary($codeBackup);

                    $backups[$backupFile->name] = $backupFile;
                }
            }
        }
        krsort($backups);

        /* Fresh latest version of QuCheng platform in session.  */
        $this->session->set('platformLatestVersion', $this->loadModel('store')->platformLatestVersion());

        $this->view->title      = $this->lang->backup->common;
        $this->view->position[] = $this->lang->backup->common;
        $this->view->backups    = $backups;
        $this->display();
    }

    /**
     * Backup.
     *
     * param   string $reload yes|no
     * @access public
     * @return void
     */
    public function backup($reload = 'no')
    {
        if($reload == 'yes') session_write_close();
        set_time_limit(0);
        $nofile = strpos($this->config->backup->setting, 'nofile') !== false;
        $nosafe = strpos($this->config->backup->setting, 'nosafe') !== false;

        $fileName = date('YmdHis') . mt_rand(0, 9);
        $backFileName = "{$this->backupPath}{$fileName}.sql";
        if(!$nosafe) $backFileName .= '.php';
        $result = $this->backup->backSQL($backFileName);
        if(!$result->result)
        {
            if($reload == 'yes')
            {
                echo js::alert(sprintf($this->lang->backup->error->noWritable, $this->backupPath));
                return print(js::reload('parent'));
            }
            else
            {
                printf($this->lang->backup->error->noWritable, $this->backupPath);
            }
        }
        if(!$nosafe) $this->backup->addFileHeader($backFileName);

        if(!$nofile)
        {
            $backFileName = "{$this->backupPath}{$fileName}.file";

            $result = $this->backup->backFile($backFileName);
            if(!$result->result)
            {
                if($reload == 'yes')
                {
                    echo js::alert(sprintf($this->lang->backup->error->backupFile, $result->error));
                    return print(js::reload('parent'));
                }
                else
                {
                    printf($this->lang->backup->error->backupFile, $result->error);
                }
            }

            $backFileName = "{$this->backupPath}{$fileName}.code";

            $result = $this->backup->backCode($backFileName);
            if(!$result->result)
            {
                if($reload == 'yes')
                {
                    echo js::alert(sprintf($this->lang->backup->error->backupCode, $result->error));
                    return print(js::reload('parent'));
                }
                else
                {
                    printf($this->lang->backup->error->backupCode, $result->error);
                }
            }
        }

        /* Delete expired backup. */
        $backupFiles = glob("{$this->backupPath}*.*");
        if(!empty($backupFiles))
        {
            $time  = time();
            $zfile = $this->app->loadClass('zfile');
            foreach($backupFiles as $file)
            {
                /* Only delete backup file. */
                $fileName = basename($file);
                if(!preg_match('/[0-9]+\.(sql|file|code)/', $fileName)) continue;

                /* Remove before holdDays file. */
                if($time - filemtime($file) > $this->config->backup->holdDays * 24 * 3600)
                {
                    $rmFunc = is_file($file) ? 'removeFile' : 'removeDir';
                    $zfile->{$rmFunc}($file);
                    if($rmFunc == 'removeDir') $this->backup->processSummary($file, 0, 0, array(), 0, 'delete');
                }
            }
        }

        if($reload == 'yes') return print(js::reload('parent'));
        echo $this->lang->backup->success->backup . "\n";
    }

    /**
     * Restore platform database and file.
     *
     * @param  string $fileName
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function restore($fileName, $confirm = 'no')
    {
        if($confirm == 'no') return $this->send(array('result' => 'fail', 'message' => $this->lang->backup->confirmRestore));

        set_time_limit(0);

        /* Restore database. */
        if(file_exists("{$this->backupPath}{$fileName}.sql.php"))
        {
            $sqlBackup = "{$this->backupPath}{$fileName}.sql.php";
            $this->backup->removeFileHeader($sqlBackup);
            $result = $this->backup->restoreSQL($sqlBackup);
            $this->backup->addFileHeader($sqlBackup);
            if(!$result->result) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreSQL, $result->error)));
        }
        elseif(file_exists("{$this->backupPath}{$fileName}.sql"))
        {
            $result = $this->backup->restoreSQL("{$this->backupPath}{$fileName}.sql");
            if(!$result->result) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreSQL, $result->error)));
        }

        /* Restore attatchments. */
        if(file_exists("{$this->backupPath}{$fileName}.file.zip.php"))
        {
            $fileBackup = "{$this->backupPath}{$fileName}.file.zip.php";
            $this->backup->removeFileHeader($fileBackup);
            $result = $this->backup->restoreFile($fileBackup);
            $this->backup->addFileHeader($fileBackup);
            if(!$result->result) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreFile, $result->error)));
        }
        elseif(file_exists("{$this->backupPath}{$fileName}.file.zip"))
        {
            $result = $this->backup->restoreFile("{$this->backupPath}{$fileName}.file.zip");
            if(!$result->result) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreFile, $result->error)));
        }
        elseif(file_exists("{$this->backupPath}{$fileName}.file"))
        {
            $result = $this->backup->restoreFile("{$this->backupPath}{$fileName}.file");
            if(!$result->result) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreFile, $result->error)));
        }

        $this->backup->processRestoreSummary('', '', 'delete');

        return print(js::reload('parent'));
    }

    /**
     * remove PHP header.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function rmPHPHeader($fileName)
    {
        if(file_exists($this->backupPath . $fileName . '.sql.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.sql.php');
            rename($this->backupPath . $fileName . '.sql.php', $this->backupPath . $fileName . '.sql');
        }
        if(file_exists($this->backupPath . $fileName . '.file.zip.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.file.zip.php');
            rename($this->backupPath . $fileName . '.file.zip.php', $this->backupPath . $fileName . '.file.zip');
        }
        if(file_exists($this->backupPath . $fileName . '.code.zip.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.code.zip.php');
            rename($this->backupPath . $fileName . '.code.zip.php', $this->backupPath . $fileName . '.code.zip');
        }

        return print(js::reload('parent'));
    }

    /**
     * Delete.
     *
     * @param  string $fileName
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($fileName, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->backup->confirmDelete, inlink('delete', "fileName=$fileName&confirm=yes")));

        /* Delete database file. */
        $dbFilePHP = $this->backupPath . $fileName . '.sql.php';
        $dbFile    = $this->backupPath . $fileName . '.sql';
        if(file_exists($dbFilePHP))
        {
            $isDelete = unlink($dbFilePHP);
            if($isDelete)
            {
                $this->backup->processSQLSummary($dbFilePHP, '', 'delete');
            }
            else
            {
                return print(js::alert(sprintf($this->lang->backup->error->noDelete, $dbFilePHP)));
            }
        }
        elseif(file_exists($dbFile))
        {
            $isDelete = unlink($dbFile);
            if($isDelete)
            {
                $this->backup->processSQLSummary($dbFile, '', 'delete');
            }
            else
            {
                return print(js::alert(sprintf($this->lang->backup->error->noDelete, $dbFile)));
            }
        }

        /* Delete attatchments file. */
        if(file_exists($this->backupPath . $fileName . '.file.zip.php') and !unlink($this->backupPath . $fileName . '.file.zip.php'))
        {
            return print(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.file.zip.php')));
        }
        if(file_exists($this->backupPath . $fileName . '.file.zip') and !unlink($this->backupPath . $fileName . '.file.zip'))
        {
            return print(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.file.zip')));
        }
        if(file_exists($this->backupPath . $fileName . '.file'))
        {
            $zfile = $this->app->loadClass('zfile');
            $zfile->removeDir($this->backupPath . $fileName . '.file');
            $this->backup->processSummary($this->backupPath . $fileName . '.file', 0, 0, array(), 0, 'delete');
        }

        /* Delete code file. */
        if(file_exists($this->backupPath . $fileName . '.code.zip.php') and !unlink($this->backupPath . $fileName . '.code.zip.php'))
        {
            return print(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.code.zip.php')));
        }
        if(file_exists($this->backupPath . $fileName . '.code.zip') and !unlink($this->backupPath . $fileName . '.code.zip'))
        {
            return print(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.code.zip')));
        }
        if(file_exists($this->backupPath . $fileName . '.code'))
        {
            $zfile = $this->app->loadClass('zfile');
            $zfile->removeDir($this->backupPath . $fileName . '.code');
            $this->backup->processSummary($this->backupPath . $fileName . '.code', 0, 0, array(), 0, 'delete');
        }

        return print(js::reload('parent'));
    }

    /**
     * Change hold days.
     *
     * @access public
     * @return void
     */
    public function change()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('setting')->setItem('system.backup.holdDays', $data->holdDays);
            return print(js::reload('parent.parent'));
        }

        $this->display();
    }

    /**
     * Setting backup
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $data = fixer::input('post')->join('setting', ',')->get();

            /*save change*/
            if(isset($data->holdDays)) $this->loadModel('setting')->setItem('system.backup.holdDays', $data->holdDays);

            $setting = '';
            if(isset($data->setting)) $setting = $data->setting;
            $this->loadModel('setting')->setItem('system.backup.setting', $setting);

            return print(js::reload('parent.parent'));
        }
        $this->display();
    }

    /**
     * Ajax get progress.
     *
     * @access public
     * @return void
     */
    public function ajaxGetProgress()
    {
        session_write_close();

        $progressMessage = new stdclass();

        $files = glob($this->backupPath . '/*.*');
        rsort($files);

        $fileName = basename($files[0]);
        $fileName = substr($fileName, 0, strpos($fileName, '.'));

        $sqlFileName = $this->backupPath . $fileName . '.sql';
        if(!file_exists($sqlFileName)) $sqlFileName .= '.php';
        $sqlFileName = $this->backup->getBackupFile($fileName, 'sql');
        if($sqlFileName)
        {
            $summary = $this->backup->getBackupSummary($sqlFileName);
            $progressMessage->sql = sprintf($this->lang->backup->progressSQL, $this->backup->processFileSize($summary['size']));
        }

        $attachFileName = $this->backup->getBackupFile($fileName, 'file');
        if($attachFileName)
        {
            $log = $this->backup->getBackupDirProgress($attachFileName);
            $progressMessage->sql  = $this->lang->backup->done;
            $progressMessage->file = sprintf($this->lang->backup->progressAttach, zget($log, 'allCount', 0), zget($log, 'count', 0));
        }

        $codeFileName = $this->backup->getBackupFile($fileName, 'code');
        if($codeFileName)
        {
            $log = $this->backup->getBackupDirProgress($codeFileName);
            $progressMessage->sql  = $this->lang->backup->done;
            $progressMessage->file = $this->lang->backup->done;
            $progressMessage->code = sprintf($this->lang->backup->progressCode, zget($log, 'allCount', 0), zget($log, 'count', 0));
        }

        return print(json_encode($progressMessage));
    }

    /**
     * Ajax get restore progress.
     *
     * @access public
     * @return void
     */
    public function ajaxGetRestoreProgress()
    {
        $summaryFile = $this->backup->getBackupPath() . 'restoreSummary';
        $progress = new stdclass();

        $summary = json_decode(file_get_contents($summaryFile), 'true');
        $progress->sql  = !empty($summary['sql']) ?  $summary['sql'] : 'doing';
        $progress->file = !empty($summary['file']) ? $summary['file'] : 'doing';

        $progress->sql  = zget($this->lang->backup->restoreProgress, $progress->sql);
        $progress->file = zget($this->lang->backup->restoreProgress, $progress->file);

        return print(json_encode($progress));
    }

    /**
     * Get upgrading status by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxUpgradeStatus()
    {
        $chartVersion = $this->setting->getItem('owner=system&module=backup&section=global&key=chartVersion');

        /* Upgrade success. */
        if(getenv('CHART_VERSION') == $chartVersion)
        {
            session_destroy();
            $this->setting->deleteItems('owner=system&module=backup&section=global&key=chartVersion');
            $this->setting->deleteItems('owner=system&module=backup&section=global&key=upgradedAt');
            return $this->send(array('result' => 'success', 'message' => $this->lang->backup->success->upgrade, 'status' => 'success'));
        }

        $upgradedAt = $this->setting->getItem('owner=system&module=backup&section=global&key=upgradedAt');
        /* Jump to login if upgrade overtime 5 miniutes. */
        if($this->backup->isGradeOvertime())
        {
            session_destroy();
            $this->setting->deleteItems('owner=system&module=backup&section=global&key=chartVersion');
            $this->setting->deleteItems('owner=system&module=backup&section=global&key=upgradedAt');
            $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->upgradeOvertime, 'status' => 'overtime'));
        }

        return $this->send(array('result' => 'fail', 'message' => $this->lang->backup->upgrading, 'status' => 'upgrading'));
    }

    /**
     * Upgrade platform by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxUpgradePlatform()
    {
        //if(version_compare($this->session->platformLatestVersion, $this->config->platformVersion, '<='))
        //{
        //    $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->beenLatestVersion));
        //}

        set_time_limit(0);
        /* Backup database. */
        $fileName = date('YmdHis') . mt_rand(0, 9);
        $backFileName = "{$this->backupPath}{$fileName}.sql";
        $result = $this->backup->backSQL($backFileName, 'upgrade');

        $logExtra = array('result' => 'success', 'data' => array('oldVersion' => getenv('APP_VERSION'), 'newVersion' => $this->session->platformLatestVersion->version));

        $success = $this->loadModel('cne')->upgradePlatform($this->session->platformLatestVersion->version);
        if($success)
        {
            $this->setting->setItem('system.backup.global.chartVersion', $this->session->platformLatestVersion->version);
            $this->setting->setItem('system.backup.global.upgradedAt', time());
            $this->action->create('backup', 0, 'upgrade', '', json_encode($logExtra));
            $this->send(array('result' => 'success', 'message' => $this->lang->backup->success->upgrade, 'version' => $this->session->platformLatestVersion));
        }

        $logExtra['result'] = 'fail';
        $this->action->create('backup', 0, 'upgrade', '', json_encode($logExtra));
        $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->upgradeFail));
    }

    /**
     * Degrade platform version. Only for debug!!!
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function ajaxDegradePlatform($version = '')
    {
        if(empty($version)) $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->requireVersion));

        $success = $this->loadModel('cne')->setPlatformVersion($version);
        if($success)
        {
            session_destroy();
            $this->send(array('result' => 'success', 'message' => $this->lang->backup->success->degrade . $version, 'locate' => '/'));
        }

        $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->degradeFail . $version));
    }
}
