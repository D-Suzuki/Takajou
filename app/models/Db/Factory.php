<?php
namespace Db;

class Factory {
    public static function createInstance($class) {
        return new $class();
    } 
}
