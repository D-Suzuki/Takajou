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


    /**
     * コンストラクタ
     * @param \Takajou\Db\AccessInterface $dbAccessObj
     * @param string $dbCode
     */
    final public function __construct(\Takajou\Db\AccessInterface $dbAccessObj, $dbCode) {
        $this->dbAccessObj = $dbAccessObj;
        // 同一DBの接続は使いまわすためgetSharedで取得
        $this->connectionId = $dbAccessObj->createSharedConnection($dbCode);
    }


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
        return $this->dbAccessObj->select($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * クエリの実行(SELECT) - 1行のみ取得
     * @return array
     */
    public function selectRow() {
        return $this->dbAccessObj->selectRow($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * PDOステートメント取得
     * @return PDOStatement
     */
    public function getPdoStatement() {
        return $this->dbAccessObj->getPdoStatement($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * クエリの実行(INSERT / UPDATE / DELETE)
     * @return
     */
    public function exec() {
        return $this->dbAccessObj->exec($this->connectionId, $this->query, $this->bindParams);
    }


    /**
     * ラストインサートID取得
     * @return
     */
    public function getLastInsertId() {
        return $this->dbAccessObj->getLastInsertId($this->connectionId);
    }

####################
# コネクション操作 #
####################
    /**
     * トランザクションスタート
     */
    public function beginTransaction() {
        $this->dbAccessObj->beginTransaction($this->connectionId);
    }


    /**
     * 切断
     */
    public function closeConnection() {
        $this->dbAccessObj->closeConnection($this->connectionId);
    }


    /**
     * 再接続
     * @param boorean $isBegin
     */
    public function reConnect($isBegin = false) {
        $this->dbAccessObj->reConnect($this->connectionId);
        if ($isBegin) {
            $this->beginTransaction();
        }
    }

    /* public function getSqlBuilder() {
        return $this->getDi()->getShared('sqlBuilder');
    } */

}
