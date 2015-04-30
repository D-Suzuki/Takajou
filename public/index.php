<?php

error_reporting(E_ALL);

try {

    /**
     * デフォルトDIコンテナ
     */
    $di = new \Phalcon\DI\FactoryDefault();

    /**
     * ルーティング登録
     */
    $routingClosure = include __DIR__ . "/../application/config/routing.php";
    $di->set('router', $routingClosure);

    /**
     * モジュール設定
     */
    $applicationObj = new \Phalcon\Mvc\Application($di);
    $applicationObj->registerModules(array(
        'api' => array(
            'className' => 'Multiple\Api\Module',
            'path'      => __DIR__ . '/../application/apps/api/Module.php'),
        'admin'  => array(
            'className' => 'Multiple\Admin\Module',
            'path'      => __DIR__ . '/../application/apps/admin/Module.php')
    ));

    /**
     * リクエスト処理
     */
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
