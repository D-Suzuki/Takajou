<?php
namespace Db;

class Factory {
    
    /**
     * 
     * @param unknown $dbName
     * @param unknown $tableName
     * @return boolean|unknown
     */
    public static function createInstance($dbName, $tableName) {

        // クラスの存在チェック
        if ($dbName == null || $tableName == null) {
            return false;
        }
        $class = '\Db\\' . self::toUpperCamel($dbName) . '\\' . self::toUpperCamel($tableName);
        if (class_exists($class) == false) {
            return false;
        }

        return new $class();
    }

    private static function toUpperCamel($snakeCase) {
        return preg_replace( '/ /', '', ucwords( preg_replace( '/_/', ' ', $snakeCase ) ) );
    }
}


