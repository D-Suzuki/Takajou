<?php
namespace Takajou\Db;

abstract class Base extends \Phalcon\Di\Injectable {

    /**
     * コネクションID
     * @var int
     */
    private $connectionId = null;


    /**
     * クエリ
     * @var string
     */
    private $query;


    /**
     * バインドパラメーター
     * @var array
     */
    private $bindParams   = array();


    /* -------------------------------------------------- */


    public function __construct() {
        // 同一DBの接続は使いまわすためgetSharedで取得
        $this->connectionId = $this->getDbAccessObj()->createSharedConnection($this->getConnectDbName());
    }


    // 接続先のDB名を取得する
    // config.phpで指定したdatabasesの2階層目の配列キーを指定する
    abstract protected function getConnectDbName();


############
# アクセサ #
############
    /**
     * クエリへのアクセサ
     * @param string $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }
    
    public function getQuery() {
        return $this->query;
    }


    /**
     * クエリの実行
     */
    public function exec() {
        return $this->getDbAccessObj()->exec($this->connectionId, $this->query, $this->bindParams);
    }


####################
# コネクション操作 #
####################
    /**
     * トランザクションスタート
     */
    public function beginTransaction() {
        $this->getDbAccessObj()->beginTransaction($this->connectionId);
    }


    /**
     * 切断
     */
    public function closeConnection() {
        $this->getDbAccessObj()->closeConnection($this->connectionId);
    }


    /**
     * 再接続
     * @param boorean $isBegin
     */
    public function reConnect($isBegin = false) {
        $this->getDbAccessObj()->reConnect($this->connectionId);
        if ($isBegin) {
            $this->beginTransaction();
        }
    }


    public function getSqlBuilder() {
        return $this->getDi()->getShared('sqlBuilder');
    }


    private function getDbAccessObj() {
        return $this->di->getShared('dbAccess');
    }
}
