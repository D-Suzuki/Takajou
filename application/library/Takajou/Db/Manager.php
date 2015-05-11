<?php
namespace Takajou\Db;

/**
 * DBの状態管理クラス
 * @author suzuki
 */
class Manager implements ManagerInterface {

##############
# プロパティ #
##############
    /**
     * データベースの設定値オブジェクト
     * @var \Phalcon\Config
     */
    private $dbConfigs = null;


    /**
     * クラスタモード
     * [ NONE/MASTER/SLAVE ]
     * @var string
     */
    private $clusterMode = \Takajou\Def\Db\ClusterMode::NONE;


    /**
     * コネクションプール
     * @var array[$connectionId] = \Phalcon\Db\Adapter $connection
     */
    private $connectionPool = array();


    /**
     * トランザクションスタートしたコネクションのID配列
     * @var array($connectionId1, $connectionId2, ...)
     */
    private $beginedConnectionIds = array();


    /* -------------------------------------------------- */


    /**
     * コンストラクタ
     * DBに関する設定値を引数とする
     * @param \Phalcon\Config $dbConfigs
     */
    public function __construct(\Phalcon\Config $dbConfigs) {
        if (!$dbConfigs) {
//TODO:
        }
        $this->dbConfigs = $dbConfigs;
    }

############
# アクセサ #
############
    /**
     * configプロパティへのアクセサメソッド
     * configプロパティはイミュータブルのためgetterのみ
     * @param \Phalcon\Config $dbConfig
     */
    private function getDbConfigs() {
        return $this->dbConfigs;
    }


    /**
     * clusterModeへのアクセサメソッド
     * 外部からは
     * ・clusterModeOff
     * ・masterModeOn
     * ・slaveModeOn
     * でclusterModeの状態を変更するためsetterはprivate
     * @param string $clusterMode
     */
    private function setClusterMode($clusterMode) {
        $this->clusterMode = $clusterMode;
    }
    public function getClusterMode() {
        return $this->clusterMode;
    }


    /**
     * コネクションプールへのsetter
     * @param unknown $connectionId
     * @param unknown $connection
     */
    public function setConnection($connectionId, $connection) {
        $this->connectionPool[$connectionId] = $connection;
    }


    /**
     * コネクションプールへのgetter
     * @param  unknown $connectionId
     * @return \Phalcon\Db\Adapter
     */
    public function getConnection($connectionId) {
        return $this->connectionPool[$connectionId];
    }


    /**
     * コネクションプールへのgetter
     * @return array[\Phalcon\Db\Adapter]
     */
    public function getConnectionPool() {
    	return $this->connectionPool;
    }


    /**
     * トランザクションスタートしているコネクションIDの配列を返却
     */
    public function getBeginedConnectionIds() {
        return $this->beginedConnectionIds;
    }


################
# dbConfig取得 #
################
    public function getDbConfig($dbCode) {
        $dbConfigs  = $this->getDbConfigs();
        $dbConfig   = $dbConfigs[$this->getClusterMode()][$dbCode];
        if (!$dbConfig) {
            return false;
        }
        return $dbConfig;
    }


######################
# クラスタモード変更 #
######################
    /**
     * クラスタモードを「NONE」へ変更
     */
    public function clusterModeOff() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterMode::NONE);
    }


    /**
     * クラスタモードを「MASTER」へ変更
     */
    public function masterModeOn() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterMode::MASTER);
    }


    /**
     * クラスタモードを「SLAVE」へ変更
     */
    public function slaveModeOn() {
        $this->setClusterMode(\Takajou\Def\Db\ClusterMode::SLAVE);
    }


##########################
# コネクション状態の変更 #
##########################
    /**
     * トランザクションスタート
     * @param int $connectionId
     */
    public function addBeginedTransaction($connectionId) {
        if (!in_array($connectionId, $this->beginedConnectionIds)) {
            $this->beginedConnectionIds[] = $connectionId;
        }
    }


    /**
     * トランザクションエンド
     * @param int $connectionId
     */
    public function deleteBeginedTransaction($connectionId) {
        if (($key = array_search($connectionId, $this->beginedConnectionIds)) !== false) {
            unset($this->beginedConnectionIds[$key]);
        }
    }


    /**
     * トランザクションスタートしているコネクションがあるかの判定
     * @return boolean
     */
    public function hasBeginedTransaction() {
        return $this->beginedConnectionIds ? true : false;
    }
}
