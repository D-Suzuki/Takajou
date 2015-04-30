<?php
namespace Takajou\Db;

/**
 * DBの状態管理クラスインターフェース
 * @author suzuki
 */
interface ManagerInterface  {

    //アクセサ 
    public function getClusterMode();
    public function setConnection($connectionId, $connection);
    public function getConnection($connectionId);
    public function getConnectionPool();
    public function getBeginedConnectionIds();

    // dbConfig取得
    public function getDbConfig($dbName);

    // クラスタモード変更
    public function clusterModeOff();
    public function masterModeOn();
    public function slaveModeOn();

    // コネクション状態の変更
    public function addBeginedTransaction($connectionId);
    public function deleteBeginedTransaction($connectionId);
    public function hasBeginedTransaction();
}
