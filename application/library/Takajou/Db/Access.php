<?php
namespace Takajou\Db;

/**
 * DBへのアクセスクラス
 * @author suzuki
 */
class Access implements \Takajou\Db\AccessInterface {

    /**
     * DBマネージャーオブジェクト
     * @var \Takajou\Db\Manager
     */
    private $dbManagerObj = null;


    /**
     * DB全体の設定値
     * @var \Phalcon\Config
     */
    private $dbConfigs = null;


    /* -------------------------------------------------- */


    /**
     * コンストラクタ
     * DBに関する設定値を引数とする
     * @param \Phalcon\Config $dbConfigs
     */
    public function __construct(\Phalcon\DiInterface $di, \Takajou\Db\ManagerInterface $dbManagerObj) {
        $this->di           = $di;
        $this->dbManagerObj = $dbManagerObj;
    }


####################
# コネクション生成 #
####################
    /**
     * コネクション生成(シングルトンタイプ)
     * 生成したコネクションはコネクションプールへ格納
     * @param  string $dbCode
     * @return int    $connectionId
     */
    public function createSharedConnection($dbCode, $isBegin = false) {
        $dbConfig = $this->dbManagerObj->getDbConfig($dbCode);
        if (!$dbConfig) {
//TODO:throw exception
        }
        $connection = $this->di->getShared($dbConfig->diName);

        // トランザクションスタート
        if ($isBegin) {
            $this->beginTransaction($connection->getConnectionId());
        }

        return $connection->getConnectionId();
    }


    /**
     * コネクション生成(非シングルトンタイプ)
     * 生成したコネクションはコネクションプールへ格納
     * @param  string $dbCode
     * @return int    $connectionId
     */
    public function createConnection($dbCode, $isBegin = false) {
        $dbConfig = $this->dbManagerObj->getDbConfig($dbCode);
        if (!$dbConfig) {
//TODO:throw exception
        }
        $connection = $this->di->get($dbConfig->diName);

        // トランザクションスタート
        if ($isBegin) {
            $this->beginTransaction($connection->getConnectionId());
        }

        return $connectionId;
    }


####################
# コネクション操作 #
####################
    /**
     * トランザクションスタート
     * @param unknown $connectionId
     */
    public function beginTransaction($connectionId) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $connection->begin();
    }


    /**
     * 切断
     * @param unknown $connectionId
     */
    public function closeConnection($connectionId) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $connection->close();
    }


    /**
     * 再接続
     * @param unknown $connectionId
     */
    public function reConnect($connectionId) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $connection->connect();
    }


    /**
     * コミット
     * @param unknown $connectionId
     */
    public function commitTransaction($connectionId) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $connection->commit();
    }


    /**
     * ロールバック
     * @param unknown $connectionId
     */
    public function rollbackTransaction($connectionId) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $connection->rollback();
    }


################
# 実行メソッド #
################
    /**
     * SELECT系クエリ
     * @param  unknown $connectionId
     * @param  unknown $query
     * @param  unknown $bindParams
     * @return array   
     */
    public function select($connectionId, $query, $bindParams) {

        $connection = $this->dbManagerObj->getConnection($connectionId);
        $result = $connection->query($query, $bindParams);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result->fetchAll();
    }


    /**
     * SELECT系クエリ
     * @param  unknown $connectionId
     * @param  unknown $query
     * @param  unknown $bindParams
     * @return array
     */
    public function selectRow($connectionId, $query, $bindParams) {
    
        $connection = $this->dbManagerObj->getConnection($connectionId);
        $result = $connection->query($query, $bindParams);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
    
        return $result->fetch();
    }


    /**
     * PDOステートメントの返却
     * @param unknown $connectionId
     * @param unknown $query
     * @param unknown $bindParams
     * @return PDOStatement
     */
    public function getPdoStatement($connectionId, $query, $bindParams) {

        $connection = $this->dbManagerObj->getConnection($connectionId);
        $statement  = $connection->prepare($query);
        if ($bindParams) {
            $statement = $connection->executePrepared($statement, $bindParams);
        }

        return $statement;
    }


    /**
     * 実行メソッド
     * @param unknown $connectionId
     * @param unknown $query
     * @param unknown $bindParams
     * @param boolean
     */
    public function exec($connectionId, $query, $bindParams) {

        $connection = $this->dbManagerObj->getConnection($connectionId);
        return $connection->execute($query);
    }


    /**
     * ラストインサートID取得処理
     * @return int
     */
    public function getLastInsertId($connectionId) {

        $connection  = $this->dbManagerObj->getConnection($connectionId);
        $lastInserId = $connection->lastInsertId();

        return $lastInserId;
    }


##############
# 一括実行系 #
##############
    /**
     * 全コミット
     */
    public function allCommit() {
        // アクティブトランザクションがある場合
        if ($this->dbManagerObj->hasBeginedTransaction()) {
            foreach ($this->dbManagerObj->getBeginedConnectionIds() as $connectionId) {
                do {
                    $connection = $this->dbManagerObj->getConnection($connectionId);
                    $isNesting = $connection->getTransactionLevel() > 1 ? true : false;
                    $connection->commit($isNesting);
                } while ($connection->getTransactionLevel() != 0);
                // ネストされている場合も考慮してトランザクションレベルが0になるまで行う
            }
        }
    }

    /**
     * 全ロールバック
     */
    public function allRollback() {
    	// アクティブトランザクションがある場合
        if ($this->dbManagerObj->hasBeginedTransaction()) {
            foreach ($this->dbManagerObj->getBeginedConnectionIds() as $connectionId) {
                do {
                    $connection = $this->dbManagerObj->getConnection($connectionId);
                    $isNesting = $connection->getTransactionLevel() > 1 ? true : false;
                    $connection->rollback($isNesting);
                } while ($connection->getTransactionLevel() != 0);
                // ネストされている場合も考慮してトランザクションレベルが0になるまで行う
            }
        }
    }

    public function destroyTransaction($connectionId) {
        unset($this->startedTransactions[$connectionId]);
    }
}
