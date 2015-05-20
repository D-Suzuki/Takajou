<?php
namespace Frame;

class Factory extends \Takajou\Factory\Base {

##############
# プロパティ #
##############
    /**
     * 自分自身のインスタンス
     * @var \Db\Factory
     */
    private static $instanceSelf;


    /**
     * 自分自身のインスタンスはシングルトンで生成させるため
     * privateで__constructを定義
     */
    private function __construct() {
    
    }


############
# メソッド #
############
    /**
     * インスタンス生成
     * @param unknown $dbName
     * @param unknown $tableName
     * @return boolean|unknown
     */
    public static function createInstance($tableName, $pkValue = null) {

        // クラス名生成
        if (!$tableName) return false;
        $className = parent::makeClassName('Frame', array($tableName));

        // クラスの存在チェック
        if (class_exists($className) === false) {
            return false;
        }

        // DBオブジェクト取得(Frameオブジェクトはgsaのtrunデータベースのみしか生成しない)
        $dbObj       = \Db\Factory::createInstance('gsa', 'trun', $tableName);
        $returnClass = new $className($dbObj, $pkValue);

        return $returnClass;
    }
}
