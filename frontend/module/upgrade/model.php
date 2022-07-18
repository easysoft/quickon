<?php
/**
 * The model file of upgrade module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
class upgradeModel extends model
{
    static $errors = array();

    /**
     *
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
    }

    /**
     * Upgrade in CLI.
     *
     * @access public
     * @return void
     */
    public function cliUpgrade()
    {
        echo "[" . date('Y-m-d H:i:s') . "] INFO: Starting upgrade...\n";
        echo "[" . date('Y-m-d H:i:s') . "] INFO: Backuping database...";
        $this->backupDatabase();
        echo "OK\n";
        $fromVersion = $this->loadModel('setting')->getVersion();
        echo "[" . date('Y-m-d H:i:s') . "] INFO: Updating database...";
        $this->execute($fromVersion);
        $this->setting->setItem('system.common.global.version', $this->config->version);
        echo "OK\n";
        echo "[" . date('Y-m-d H:i:s') . "] INFO: Done \n";
    }

    /**
     * Backup database.
     *
     * @access public
     * @return void
     */
    public function backupDatabase()
    {
        $fileName   = date('YmdHis') . mt_rand(0, 9);
        $backupPath = $this->loadModel('backup')->getBackupPath();
        $result     = $this->loadModel('backup')->backSQL("{$backupPath}{$fileName}.sql", 'upgrade');
    }

    /**
     * Get mysql version.
     *
     * @access public
     * @return string
     */
    private function getMysqlVersion()
    {
        $sql = "SELECT VERSION() AS version";
        $result = $this->dbh->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }

    /**
     * Get versions to update
     *
     * @param  mixed $openVersion
     * @access public
     * @return array
     */
    public function getVersionsToUpdate($openVersion, $fromEdition)
    {
        $versions = array();
        /* Always update open sql. */
        foreach($this->lang->upgrade->fromVersions as $version => $versionName)
        {
            if(!is_numeric($version[0])) continue;
            if(version_compare(str_replace('_', '.', $version), str_replace('_', '.', $openVersion)) < 0) continue;
            $versions[$version] = array('pro' => array(), 'biz' => array(), 'max' => array());
        }
        if($fromEdition == 'open') return $versions;

        //@todo pro, bix, max are chareged editions in future.

        return $versions;
    }

    /**
     * The execute method. According to the $fromVersion call related methods.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        set_time_limit(0);

        if(!isset($this->app->user)) $this->loadModel('user')->su();

        $editions    = array('p' => 'pro', 'b' => 'biz', 'm' => 'max');
        $fromEdition = is_numeric($fromVersion[0]) ? 'open' : $editions[$fromVersion[0]];

        /* Execute. */
        $fromOpenVersion = is_numeric($fromVersion[0]) ? $fromVersion : $this->config->upgrade->{$fromEdition . 'Version'}[$fromVersion];
        $versions        = $this->getVersionsToUpdate($fromOpenVersion, $fromEdition);
        foreach($versions as $openVersion => $chargedVersions)
        {
            $this->saveLogs("Execute $openVersion");
            $this->execSQL($this->getUpgradeFile(str_replace('_', '.', $openVersion)));
        }
    }

    /**
     * Save Logs.
     *
     * @param  string    $log
     * @access public
     * @return void
     */
    public function saveLogs($log)
    {
        $logFile = $this->app->getTmpRoot() . 'log/upgrade.' . date('Ymd') . '.log.php';
        $log     = date('Y-m-d H:i:s') . ' ' . trim($log) . "\n";
        if(!file_exists($logFile)) $log = "<?php\ndie();\n?" . ">\n" . $log;

        static $fh;
        if(empty($fh)) $fh = fopen($logFile, 'a');
        fwrite($fh, $log);
    }

    /**
     * Get the upgrade sql file.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . DS . 'update' . $version . '.sql';
    }

    /**
     * Execute a sql.
     *
     * @param  string  $sqlFile
     * @access public
     * @return void
     */
    public function execSQL($sqlFile)
    {
        if(!file_exists($sqlFile)) return false;

        $this->saveLogs('Run Method ' . __FUNCTION__);
        $mysqlVersion = $this->getMysqlVersion();
        $ignoreCode   = '|1050|1054|1060|1091|1061|';

        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line)
        {
            $line       = trim($line);
            $sqls[$key] = $line;

            /* Skip sql that is note. */
            if(preg_match('/^--|^#|^\/\*/', $line) or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
            }

            $sqlToLower = strtolower($sql);
            if(strpos($sqlToLower, 'fulltext') !== false and strpos($sqlToLower, 'innodb') !== false and $mysqlVersion < 5.6)
            {
                self::$errors[] = $this->lang->install->errorEngineInnodb;
                return false;
            }

            $sql = str_replace('q_', $this->config->db->prefix, $sql);
            $sql = str_replace('__DELIMITER__', ';', $sql);
            $sql = str_replace('__TABLE__', $this->config->db->name, $sql);
            try
            {
                $this->saveLogs($sql);
                $this->dbh->exec($sql);
            }
            catch(PDOException $e)
            {
                $this->saveLogs($e->getMessage());
                $errorInfo = $e->errorInfo;
                $errorCode = $errorInfo[1];
                if(strpos($ignoreCode, "|$errorCode|") === false) self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }
}
