<?php
namespace Takajou\Model;

class Base extends \Phalcon\Mvc\Model {

    private $connection = null;
    private $tableName  = null;
    private $record     = null;

    public function onConstruct()
    {
        // 同一DBの接続は使いまわすためgetSharedで取得
        $connection = $this->getDbManager()->getSharedConnection();
        $this->setConnection($connection);
        // \Phalcon\Mvc\Modelの機能も使用可能にするようコネクションサービスを登録
        $this->setConnectionService($connection->getDiName());
        if (!$this->getTableName()) {
            $this->setTableName($this->getSource());
        }
    }

    public function getDbManager()
    {
        return $this->getDI()->get('dbManager');
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function beginTransaction($isNesting = false)
    {
        $this->getConnection()->begin($isNesting);
    }

    public function commitTransaction($isNesting = false)
    {
        $this->getConnection()->commit($isNesting);
    }

    public function closeConnection()
    {
        $this->getConnection()->close();
    }

    public function reConnect($isBegin = false)
    {
        $this->getConnection()->connect();
        if ($isBegin) {
            $this->getConnection()->begin();
        }
    }

    public function getSqlBuilder() {
        return $this->getDi()->getShared('sqlBuilder');
    }
}
