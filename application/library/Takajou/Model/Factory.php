<?php
namespace Takajou\Model;

class Factory {

    public static function createInstance($class) {
        return new $class();
    }
}
