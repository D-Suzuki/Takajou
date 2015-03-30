<?php
namespace Takajou\Db;

class Pdo extends \Phalcon\Db\Adapter\Pdo\Mysql {
    private $di     = null;
    private $diName = null;

    public function __construct(\Phalcon\Config $dbSettings, \Phalcon\DiInterface $di)
    {
        if (!$dbSettings) {
//TODO:throw exception
        }
        if (!$di) {
//TODO:throw exception
        }
        $descriptor = array(
            'host'     => $dbSettings->host,
            'username' => $dbSettings->username,
            'password' => $dbSettings->password,
            'dbname'   => $dbSettings->dbname,
            "charset"  => $dbSettings->charset,
        );
        $this->setDiName($dbSettings->diName);
        $this->setDI($di);
        parent::__construct($descriptor);
    }

    public function setDI(\Phalcon\DiInterface $di)
    {
        if($this->di === null) {
            $this->di = $di;
        }
    }

    public function getDI()
    {
        return $this->di;
    }

    private function setDiName($diName)
    {
        $this->diName = $diName;
    }

    public function getDiName()
    {
        return $this->diName;
    }

    /**
     * Start Transaction
     */
    public function begin($isNesting = false)
    {
        if (!$this->isUnderTransaction() || $isNesting) {
            parent::begin($isNesting);
            $this->getDI()->get('dbManager')->addStartedTransaction($this->getConnectionId(), $this);
        }
    }

    /**
     * Transaction Commit
     */
    public function commit($isNesting = false)
    {
        parent::commit($isNesting);
        if ($this->getTransactionLevel() == 0) {
            $this->getDI()->get('dbManager')->destroyTransaction($this->getConnectionId());
        }
    }

    /**
     * Transaction Rollback
     */
    public function rollback($isNesting)
    {
        parent::rollback($isNesting);
        if ($this->getTransactionLevel() == 0) {
            $this->getDI()->get('dbManager')->destroyTransaction($this->getConnectionId());
        }
    }

    /**
     * Connection Close
     */
    public function close()
    {
        parent::close();
        $this->getDI()->get('dbManager')->destroyTransaction($this->getConnectionId());
    }
}