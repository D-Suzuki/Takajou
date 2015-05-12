<?php
error_reporting(E_ALL);
try {

    /**
     * オートローダー
     */
    $loaderObj = new \Phalcon\Loader();
    $loaderObj->registerDirs(array(
        '../app/controllers/',
        '../app/config/',
        '../app/models/',
        '../app/library/',
    ))->register();

    /**
     * コンフィグ読込
     */
    $config    = include __DIR__ . "/../app/config/config.php";
    $configObj = new \Phalcon\Config($config);

    /**
     * DIコンテナセット
     */
    $serviceObj = new \Takajou\Bootstrap\Service();
    $serviceObj->setDefaultDI($configObj);
    $di = $serviceObj->getDI();

    /**
     * リクエスト処理
     */
    $applicationObj = new \Phalcon\Mvc\Application($di);
    $responce = $applicationObj->handle()->getContent();

    /**
     * トランザクションコミット
     */
    if ($di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allCommit();
    }
    echo $responce;

} catch (\Exception $e) {

    /**
     * トランザクションロールバック
     */
    if ($di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allRollback();
    }
    echo $e->getMessage();
}