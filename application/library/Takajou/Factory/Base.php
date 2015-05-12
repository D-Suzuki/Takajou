<?php
namespace Takajou\Factory;

class Base extends \Phalcon\Di\Injectable {

##############
# プロパティ #
##############
    /**
     * Factoryクラスで生成するインスタンスの配列
     * ※キーはクラス名
     * @var array
     */
    private static $instancePool = array();


############
# アクセサ #
############
    /**
     * instancePoolへのsetter
     * @param string  $className
     * @param unknown $instance
     */
    public static function setInstance($className, $instance) {
        self::$instancePool[$className] = $instance;
    }


    /**
     * instancePoolへのgetter
     * @param string  $className
     */
    public static function getInstance($className) {
        if (!isset(self::$instancePool[$className])) {
            return false;
        }
        return self::$instancePool[$className];
    }


############
# メソッド #
############
    /**
     * クラス名を生成
     * @param  string $prefixNamespace
     * @param  array  $namespaces
     * @return string $className
     */
    public static function makeClassName($prefixNamespace, $namespaces = array()) {
        
        $className = '';
        if(!$prefixNamespace || !$namespaces) return false;
        $className .= '\\' . self::toUpperCamel($prefixNamespace);
        
        foreach ($namespaces as $value) {
            $className .= '\\' . self::toUpperCamel($value);
        }
        
        return $className;
    }

    /**
     * スネークケースをアッパーキャメルに変換
     * @param string $snakeCase
     */
    private static function toUpperCamel($snakeCase) {
        return preg_replace( '/ /', '', ucwords( preg_replace( '/_/', ' ', $snakeCase ) ) );
    }
}
