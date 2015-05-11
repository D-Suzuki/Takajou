<?php
namespace Takajou\Db;

abstract class Base {

    /**
     * コネクションID
     * @var int
     */
    private $connectionId = null;


    /**
     * \Takajou\Db\Accessインスタンス
     * @var \Takajou\Db\AccessInterface
     */
    private $dbAccessObj  = null;


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


    final public function __construct(\Takajou\Db\AccessInterface $dbAccessObj) {
        $this->dbAccessObj = $dbAccessObj;
        // 同一DBの接続は使いまわすためgetSharedで取得
        $this->connectionId = $dbAccessObj->createSharedConnection($this->getConnectDbCode());
    }


    // 接続先のDBコードを取得する
    // \Db\Iniで指定したdbの2階層目の配列キーを指定する
    abstract protected function getConnectDbCode();


############
# アクセサ #
############
    /**
     *queryへのsetter
     * @param string $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }


    /**
     * queryへのgetter
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }


    /**
     * bindParamsへのsetter
     * @param array $bindParams
     */
    public function setBindParams($bindParams) {
        $this->bindParams = $bindParams;
    }


    /**
     * bindParamsへのgetter
     * @return array
     */
    public function getBindParams() {
        return $this->bindParams;
    }


################
# 実行メソッド #
################
    /**
     * クエリの実行(SELECT)
     * @return array
     */
    public function select() {
        return $this->getDbAccessObj()->select($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * PDOステートメント取得
     * @return PDOStatement
     */
    public function getPdoStatement() {
        return $this->getDbAccessObj()->getPdoStatement($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * クエリの実行(INSERT / UPDATE / DELETE)
     * @return
     */
    public function exec() {
        return $this->getDbAccessObj()->exec($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * ラストインサートID取得
     * @return
     */
    public function getLastInsertId() {
        return $this->getDbAccessObj()->getLastInsertId($this->connectionId);
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


    /* public function getSqlBuilder() {
        return $this->getDi()->getShared('sqlBuilder');
    } */


    private function getDbAccessObj() {
        return $this->dbAccessObj;
    }
}
