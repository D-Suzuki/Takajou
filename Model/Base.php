<?php
namespace Takajou\Model;

class Base extends \Phalcon\Mvc\Model {

    private $connection = null;

    public function onConstruct()
    {
        // 同一DBの接続は使いまわすためgetSharedで取得
        $connection = $this->getDbManager()->getSharedConnection();
        $this->setConnection($connection);
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

    public function beginTransaction($isNesting = false)
    {
        $this->getConnection()->begin($isNesting);
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
