<?php
namespace Takajou\Model;

class Base extends \Phalcon\Mvc\Model {

    private $connection = null;
    private $tableName  = null;
    private $primaryKey = null;
    private $records    = null;

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

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function getPrimaryKey() {
        if ($this->primaryKey) {
            return $this->primaryKey;
        }
        $tableName = $this->getTableName();
        foreach ($this->getConnection()->describeColumns($tableName) as $clumnObj) {
            if ($clumnObj->isPrimary()) {
                $primaryKey = $clumnObj->getName();
                break;
            }
        }
        return $primaryKey;
    }

    public function setRecords($records)
    {
        $this->records = $records;
    }

    public function getRecords()
    {
        return $this->records;
    }

    public function setRecord($key, $value)
    {
        $this->records[$key] = $value;
    }

    public function getRecord($key)
    {
        return $this->records[$key];
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

    public function findByPrimaryKey($primary)
    {
        $sql = <<<EOF
   SELECT *
     FROM {$this->tableName}
    WHERE {$this->getPrimaryKey()} = ?
EOF;
        $params = array($primary);
        return $this->getConnection()->fetchOne($sql, \Phalcon\Db::FETCH_ASSOC, $params);
    }
}
