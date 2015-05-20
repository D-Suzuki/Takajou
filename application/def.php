<?php

namespace Def;

/**
 * 各種パス
 * @author suzuki
 *
 */
class PATH {
    const APPLICATION   = __DIR__;
    const MODULE_ADMIN  = self::APPLICATION . '/apps/admin';
    const MODULE_API    = self::APPLICATION . '/apps/api';
    const CONFIG        = self::APPLICATION . '/config';
    const LIBRARY       = self::APPLICATION . '/library';
    const LOG           = self::APPLICATION . '/log';
    const MODELS        = self::APPLICATION . '/models';
    const TASKS         = self::APPLICATION . '/tasks';
    const DOCUMENT_ROOT = self::APPLICATION . '/../public';
    const PHPMIG        = '/usr/local/include/php/Phpmig';
}

/**
 * 環境変数
 * @author suzuki
 *
 */
class ENV {
    const PRODUCT      = 'prod';
    const DEVELOP      = 'dev';
}


class STRING {
    const TYPE_UCC     = 'UpperCamelCase';
    const TYPE_LCC     = 'lowerCamelCase';
    const TYPE_SC      = 'snake_case';
}