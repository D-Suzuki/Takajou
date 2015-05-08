<?php
namespace Takajou\Db;

/**
 * DBへのアクセスクラスインターフェース
 * @author suzuki
 */
interface AccessInterface {

    // コネクション生成
    public function createSharedConnection($dbName, $isBegin = false);
    public function createConnection($dbName, $isBegin = false);
    
    // コネクション操作
    public function beginTransaction($connectionId);
    public function closeConnection($connectionId);
    public function reConnect($connectionId);
    public function commitTransaction($connectionId);
    public function rollbackTransaction($connectionId);

    // 実行メソッド
    public function select($connectionId, $query, $bindParams);
    public function getPdoStatement($connectionId, $query, $bindParams);
    public function exec($connectionId, $query, $bindParams);
    public function getLastInsertId($connectionId);

    // 一括実行系
    public function allCommit();
    public function allRollback();
}
