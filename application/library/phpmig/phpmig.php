<?php

use \Phpmig\Adapter,
\Pimple;

/**
 * 基本定数クラスロード
 */
include __DIR__ . '/../../def.php';

/**
 * ユーティリティクラスロード
 */
include \Def\PATH::APPLICATION . '/../public/utility.php';

/**
 * phpmig用のライブラリを読込
 */
include \Def\PATH::PHPMIG . '/vendor/autoload.php';

/**
 * オートローダ登録
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        \Def\PATH::APPLICATION . '/config/',
        \Def\PATH::APPLICATION . '/library/',
    )
);
$loader->register();

/**
 * 設定ファイルロード
 */
$configObj      = new \Phalcon\Config();
$configObj->merge(new \Phalcon\Config(\Ini\Db::load()));

/**
 * コンテナにPDOを登録
 */
$container = new Pimple();
$dbConfigObj = $configObj->db->master->gsdb_trun;
$container['gsdb_trun'] = $container->share(function() use($dbConfigObj) {
    $dsn = sprintf('mysql:dbname=%s;host=%s', $dbConfigObj->dbName, $dbConfigObj->host);
    $dbh = new PDO($dsn, $dbConfigObj->username, $dbConfigObj->password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
});

/**
 * マイグレーション管理テーブルの定義
 */
$container['phpmig.adapter'] = $container->share(function() use ($container) {
    return new Adapter\PDO\Sql($container['gsdb_trun'], 'migrations');
});

/**
 * マイグレーションパス
 */
$container['phpmig.migrations_path'] = \Def\PATH::LIBRARY . '/phpmig/migrations';

return $container;
