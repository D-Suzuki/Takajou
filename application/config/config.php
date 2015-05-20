<?php

/**
 * phalcon devtools用設定ファイル
 */
require_once __DIR__ . '/../def.php';
require_once \Def\PATH::DOCUMENT_ROOT . '/Util.php';

$loaderObj = new \Phalcon\Loader();
$loaderObj->registerDirs(array(
    \Def\PATH::CONFIG,
    \Def\PATH::LIBRARY,
))->register();

$loaderObj->register();

$dbConifig      = \Ini\Db::load(); 
$masterDbConfig = $dbConifig['db']['master'];

$dbCode = $GLOBALS['argv'][3];

if (isset($masterDbConfig[$dbCode]) == FALSE) {
    echo 'CONFIG ERROR!!' . PHP_EOL;
    exit;
}

return array("database" => $masterDbConfig[$dbCode]);


