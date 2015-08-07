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


    /**
     * バインドパラメータータイプ
     * @var array
     */
    private $bindTypes    = array();

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
     * bindParmasへの追加（値単位）
     * @param unknown $bindParams
     */
    public function addBindParam($bindParam) {
        array_push($this->bindParams, $bindParam);
    }


    /**
     * bindParmasへの追加（配列単位）
     * @param unknown $bindParams
     */
    public function addBindParams($bindParams) {
        $this->bindParams = array_merge($this->bindParams, $bindParams);
    }


    /**
     * bindParamsへのgetter
     * @return array
     */
    public function getBindParams() {
        return $this->bindParams;
    }


    /**
     * bindTypesへのsetter
     * @param array $bindTypes
     */
    public function setBindTypes($bindTypes) {
    	$this->bindTypes = $bindTypes;
    }
    
    
    /**
     * bindTypesへの追加（値単位）
     * @param unknown $bindTypes
     */
    public function addBindType($bindType) {
    	array_push($this->bindTypes, $bindType);
    }


    /**
     * bindTypesへの追加（配列単位）
     * @param unknown $bindTypes
     */
    public function addBindTypes($bindTypes) {
    	$this->bindTypes = array_merge($this->bindTypes, $bindTypes);
    }
    
    
    /**
     * bindTypesへのgetter
     * @return array
     */
    public function getBindTypes() {
    	return $this->bindTypes;
    }


    /**
     * クエリとパラメーターをリセット
     */
    public function resetQueryAndParams() {
        $this->query      = null;
        $this->bindParams = array();
        $this->bindTypes  = array();
    }


################
# 実行メソッド #
################
    /**
     * クエリの実行(SELECT)
     * @return array
     */
    public function select() {
        $result = $this->dbAccessObj->select($this->connectionId, $this->query, $this->bindParams, $this->bindTypes);
        $this->resetQueryAndParams();
        return $result;
    }


    /**
     * クエリの実行(SELECT) - 1行のみ取得
     * @return array
     */
    public function selectRow() {
        $result = $this->dbAccessObj->selectRow($this->connectionId, $this->query, $this->bindParams, $this->bindTypes);
        $this->resetQueryAndParams();
        return $result;
    }


    /**
     * PDOステートメント取得
     * @return PDOStatement
     */
    public function getPdoStatement() {
        $result = $this->dbAccessObj->getPdoStatement($this->connectionId, $this->query, $this->bindParams, $this->bindTypes);
        $this->resetQueryAndParams();
        return $result;
    }


    /**
     * クエリの実行(INSERT / UPDATE / DELETE)
     * @return
     */
    public function exec() {
        $result = $this->dbAccessObj->exec($this->connectionId, $this->query, $this->bindParams, $this->bindTypes);
        $this->resetQueryAndParams();
        return $result;
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
