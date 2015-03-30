<?php
namespace Takajou\Db;

class Manager {

    private $di                  = null;
    private $config              = null;
    private $clusterMode         = \Takajou\Def\Db\ClusterType::NONE;
    private $connectDb           = 'db';
    private $isAutoBegin         = false;
    private $startedTransactions = array();

    public function __construct(\Phalcon\DiInterface $di, \Phalcon\Config $config)
    {
        if (!$di) {
//TODO:
        }
        $this->setDI($di);
        if (!$config) {
//TODO:
        }
        $this->setConfig($config);
    }

    private function setDI(\Phalcon\DiInterface $di)
    {
        $this->di = $di;
    }

    private function getDI()
    {
        return $this->di;
    }

    private function setConfig($config)
    {
        $this->config = $config;
    }

    private function getConfig()
    {
        return $this->config;
    }

    public function getDbSettings()
    {
        $config     = $this->getConfig();
        $dbSettings = $config['databases'][$this->getClusterMode()][$this->getConnectDb()];
        if(!$dbSettings) {
            return false;
        } 
        return $dbSettings;
    }

    public function clusterModeOff() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterType::NONE);
    }

    public function masterModeOn() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterType::MASTER);
    }

    public function slaveModeOn() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterType::SLAVE);
    }

    private function setClusterMode($clusterMode)
    {
        $this->clusterMode = $clusterMode;
    }

    public function getClusterMode()
    {
        return $this->clusterMode;
    }

    public function setConnectDb($connectDb)
    {
        $this->connectDb = $connectDb;
    }

    public function getConnectDb()
    {
        return $this->connectDb;
    }

    public function autoBeginOn()
    {
        $this->setIsAutoBegin(true);
    }

    public function autoBeginOff()
    {
        $this->setIsAutoBegin(false);
    }

    private function setIsAutoBegin($isAutoBegin)
    {
        $this->isAutoBegin = $isAutoBegin;
    }

    private function isAutoBegin()
    {
        return $this->isAutoBegin;
    }

    public function getSharedConnection($isBegin = false)
    {
        $dbSettings = $this->getDbSettings();
        if (!$dbSettings) {
//TODO:throw exception
        }
        $connection = $this->getDI()->getShared($dbSettings->diName);
        if ($isBegin || $this->isAutoBegin()) {
            $connection->begin();
        }
        return $connection;
    }

    public function getNewConnection($isBegin = false)
    {
        $dbSettings = $this->getDbSettings();
        if (!$dbSettings) {
//TODO:throw exception
        }
        $connection = $this->getDI()->get($dbSettings->diName);
        if ($isBegin || $this->isAutoBegin()) {
            $connection->begin();
        }
        return $connection;
    }

    public function addStartedTransaction($connectionId, $connectionObj)
    {
        $this->startedTransactions[$connectionId] = $connectionObj;
    }

    public function hasStartedTransactions()
    {
        return $this->getStartedTransactions() ? true : false;
    }

    public function getStartedTransactions()
    {
        return $this->startedTransactions;
    }

    public function getStartTedransaction($connectionId)
    {
        return $this->startedTransactions[$connectionId];
    }

    public function allCommit()
    {
        if ($this->hasStartedTransactions()) {
            foreach ($this->getStartedTransactions() as $connection) {
                do {
                    $isNesting = $connection->getTransactionLevel() > 1 ? true : false;
                    $connection->commit($isNesting);
                } while ($connection->getTransactionLevel() != 0);
            }
        }
    }

    public function allRollback()
    {
        if ($this->hasStartedTransactions()) {
            foreach ($this->getStartedTransactions() as $connection) {
                do {
                    $isNesting = $connection->getTransactionLevel() > 1 ? true : false;
                    $connection->rollback($isNesting);
                } while ($connection->getTransactionLevel() != 0);
            }
        }
    }

    public function destroyTransaction($connectionId)
    {
        unset($this->startedTransactions[$connectionId]);
    }
}
