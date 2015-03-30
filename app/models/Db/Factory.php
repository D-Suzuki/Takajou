<?php
namespace Db;

class Factory {

    public static function getInstance($class) {
        // 呼び出しはnamespace「Db」以下のクラスにだけ限定
        $callClass = '\Db\\' . trim($class, '\\');
        if (!class_exists($callClass)) {
            return false;
        }
        return new $callClass();
    }

    public static function getInstanceWithBegin($class) {
        $instance = self::getInstance($class);
        $instance->beginTransaction();
        return $instance;
    }
}
