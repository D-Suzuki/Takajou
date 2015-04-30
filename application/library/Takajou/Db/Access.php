<?php
namespace Takajou\Db;

/**
 * DBの状態管理クラス
 * @author suzuki
 */
class Access extends \Phalcon\Di\Injectable {

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
    public function __construct(\Takajou\Db\ManagerInterface $dbManager) {
        $this->dbManagerObj = $dbManager;
    }


####################
# コネクション生成 #
####################
    /**
     * コネクション生成(シングルトンタイプ)
     * 生成したコネクションはコネクションプールへ格納
     * @param  string $dbName
     * @return int    $connectionId
     */
    public function createSharedConnection($dbName, $isBegin = false) {
        $dbConfig = $this->dbManagerObj->getDbConfig($dbName);
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
     * @param  string $dbName
     * @return int    $connectionId
     */
    public function createConnection($dbName, $isBegin = false) {
        $dbConfig = $this->dbManagerObj->getDbConfig($dbName);
        if (!$dbConfig) {
//TODO:throw exception
        }
        $connection = $this->getDI()->get($dbConfig->diName);

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
    public function exec($connectionId, $query, $bindParams) {
        $connection = $this->dbManagerObj->getConnection($connectionId);
        return $connection->query($query)->fetchAll();
    }

##############
# 一括操作系 #
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
