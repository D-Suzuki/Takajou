<?php
namespace Takajou\Db;

/**
 * \Db\Managerへの状態伝達クラス
 * 
 * \Takajou\Bootstrap\ServiceクラスにてDB用イベントリスナーとして登録しており
 * \Phalcon\Db\Adapter\Pdo\[dbms]を使用してのDB操作であれば下記タイミングでPhalconがメソッドをコールする
 * ・afterConnect        DB接続時
 * ・beginTransaction    トランザクションスタート時
 * ・beforeQuery         クエリ実行前
 * ・afterQuery          クエリ実行後
 * ・rollbackTransaction ロールバック時
 * ・commitTransaction   コミット時
 * ・beforeDisconnect    DB切断時
 * ※afterConnectとbeforeDisconnectのみ\Takajou\Adapter\Pdo\Mysqlで明示的にコール
 * @author suzuki
 */
class Listener {

##############
# プロパティ #
##############
    /**
     * DBマネージャーオブジェクト
     * @var \Takajou\Db\Manager
     */
    private $dbManagerObj = null;


    /**
     * データベース毎の設定値
     * @var \Phalcon\Config
     */
    private $dbConfigObj;


    /**
     * ログオブジェクト
     * @var unknown
     */
    private $loggerObj;


    /* -------------------------------------------------- */


    /**
     * DBコネクション毎にListenerインスタンスが生成される
     * コネクションとリスナーオブジェクトは 1:1 の関係
     * @param \Phalcon\Config $dbConfig
     */
    public function __construct(\Takajou\Db\ManagerInterface $dbManager, 
                                \Phalcon\Config              $dbConfig, 
                                \Phalcon\Logger\Adapter      $loggerObj) {
        $this->dbManagerObj = $dbManager;
        $this->dbConfigObj  = $dbConfig;
        $this->loggerObj    = $loggerObj;
    }


############
# アクセサ #
############
    /**
     * loggerプロパティへのアクセサメソッド
     * loggerプロパティはイミュータブルのためgetterのみ
     * @param \Phalcon\Logger\Adapter\File $logger
     */
    protected function getLoggerObj() {
        return $this->loggerObj;
    }


    /**
     * dbConfigプロパティへのアクセサメソッド
     * dbConfigプロパティはイミュータブルのためgetterのみ
     * @param \Phalcon\Config $dbConfig
     */
    protected function getDbConfigObj() {
        return $this->dbConfigObj;
    }


####################
# イベントリスナー #
####################
    /**
     * 接続時
     * コネクションを\Db\ManagerのconnectionPoolへ放り込む
     * @param unknown $event
     * @param unknown $connection
     */
    public function afterConnect($event, $connection) {
        $this->dbManagerObj->setConnection($connection->getConnectionId(), $connection);
    }


    /**
     * トランザクションスタート時
     * \Db\Managerへトランザクションスタートしたことを知らせる
     * @param unknown $event
     * @param unknown $connection
     */
    public function beginTransaction($event, $connection) {
        $this->dbManagerObj->addBeginedTransaction($connection->getConnectionId());
    }


    /**
     * クエリ実行前
     * 
     * @param unknown $event
     * @param unknown $connection
     */
    public function beforeQuery($event, $connection) {

    }


    /**
     * クエリ実行後
     * クエリのロギングを行う
     * @param unknown $event
     * @param unknown $connection
     */
    public function afterQuery($event, $connection) {
        if ($this->getDbConfigObj()->isSqlLoging) {
            // SQLステートメントログ
            $this->getLoggerObj()->log($connection->getRealSQLStatement(), \Phalcon\Logger::INFO);
            // プレースホルダーパラメーターログ
            if ($connection->getSQLVariables()) {
                foreach ($connection->getSQLVariables() as $key => $val) {
                    $this->getLoggerObj()->log(sprintf(' value [%s] = %s', $key, $val), \Phalcon\Logger::INFO);
                }
            }
        }
    }


    /**
     * ロールバック時
     * \Db\Managerへロールバックしたことを知らせる
     * @param unknown $event
     * @param unknown $connection
     */
    public function rollbackTransaction($event, $connection) {
        $this->dbManagerObj->deleteBeginedTransaction($connection->getConnectionId());
    }


    /**
     * コミット時
     * \Db\Managerへコミットしたことを知らせる
     * @param unknown $event
     * @param unknown $connection
     */
    public function commitTransaction($event, $connection) {
        $this->dbManagerObj->deleteBeginedTransaction($connection->getConnectionId());
    }


    /**
     * 切断時
     * @param unknown $event
     * @param unknown $connection
     */
    public function beforeDisconnect($event, $connection) {
        $this->dbManagerObj->deleteBeginedTransaction($connection->getConnectionId());
    }
}
