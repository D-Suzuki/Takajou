<?php

define('VERSION', '1.0.0');

/**
 * 基本定数クラスロード
 */
include __DIR__ . "/../application/def.php";

/**
 * ユーティリティクラスロード
 */
include __DIR__ . "/../public/utility.php";

/**
 * オートローダ登録
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        \Def::APPLICATION_PATH . '/config/',
        \Def::APPLICATION_PATH . '/library',
        \Def::APPLICATION_PATH . '/models',
        \Def::APPLICATION_PATH . '/tasks',
    )
);
$loader->register();

/**
 * 設定ファイルロード
 */
$configObj      = new \Phalcon\Config();
$configObj->merge(new \Phalcon\Config(\Ini\Db::load()));

/**
 * CLI用デフォルトDIコンテナ登録
 */
$di = new \Phalcon\DI\FactoryDefault\CLI();
$serviceObj = new \Takajou\Bootstrap\Service();
$serviceObj->setDefaultDI($di, $configObj);

/**
 * コンソールアプリケーションの作成
 */
$console = new \Phalcon\CLI\Console();
$console->setDI($di);

/**
 * タスク名、アクション名、引数の取得
 */
$arguments = array();
$params = array();

foreach ($argv as $index => $arg) {
    if ($index == 1) {
        $arguments['task'] = $arg;
    } elseif ($index == 2) {
        $arguments['action'] = $arg;
    } elseif ($index >= 3) {
       $params[] = $arg;
    }
}
if(count($params) > 0) {
    $arguments['params'] = $params;
}

// タスク名、アクション名のグローバル定数
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // タスク実行
    $console->handle($arguments);

} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}