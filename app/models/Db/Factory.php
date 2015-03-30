<?php
namespace Db;

class Factory {

    public static function getInstance($className, $primaryKey = null)
    {
        // クラス名は必須
        if (!$className) {
            return false;
        }
        // 呼び出しはnamespace「Db」以下のクラスにだけ限定
        $callClass = '\Db\\' . trim($className, '\\');
        if (!class_exists($callClass)) {
            return false;
        }
        $instance = new $callClass();
        // primaryKeyが指定されていたらデータGet&Set
        if ($primaryKey) {
            $record = $instance->findByPrimaryKey($primaryKey);
            $instance->setRecords($record);
        }
        return $instance;
    }

    public static function getInstanceWithBegin($class, $primaryKey = null) {
        $instance = self::getInstance($class, $primaryKey);
        $instance->beginTransaction();
        return $instance;
    }
}
