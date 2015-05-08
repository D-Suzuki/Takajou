<?php
namespace Db;

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
    public static function createInstance($appName, $dbName, $tableName) {

        // クラス名生成
        if(!$dbName || !$tableName) return false;
        $className = self::makeClassName('Db', array($appName, $dbName, $tableName));

        // すでにクラスが生成されていれば返す
        if ($retusnClass = self::getInstance($className)) {
            return $retusnClass;
        }

        // クラスの存在チェック
        if (class_exists($className) == false) {
            return false;
        }

        // DIを取得するため自分自身のインスタンスをシングルトンで生成
        if (is_null(self::$instanceSelf)) {
            self::$instanceSelf = new self;
        }
        $instanceSelf = self::$instanceSelf;

        // \Takajou\Db\Baseクラスは\Takajou\Db\Accessクラスに依存
        $dbAccesssObj = $instanceSelf->getDI()->getShared('dbAccess');
        $returnClass = new $className($dbAccesssObj); 
        
        // インスタンスを使い回すためインスタンスプールに保存
        self::setInstance($className, $returnClass);
        return $returnClass;
    }
}
