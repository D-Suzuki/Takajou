<?php

error_reporting(E_ALL);

try {

    /**
     * 基本定数クラスロード
     */
    include __DIR__ . "/../application/def.php";

    /**
     * ユーティリティクラスロード
     */
    include __DIR__ . "/utility.php";
    
    /**
     * デフォルトDIコンテナ
     */
    $di = new \Phalcon\DI\FactoryDefault();

    /**
     * ルーティング登録
     */
    $routingClosure = include \Def\PATH::CONFIG . "/routing.php";
    $di->set('router', $routingClosure);

    /**
     * モジュール設定
     */
    $applicationObj = new \Phalcon\Mvc\Application($di);
    $applicationObj->registerModules(array(
        'api' => array(
            'className' => 'Multiple\Api\Module',
            'path'      => \Def\PATH::MODULE_API   . '/Module.php'),
        'admin'  => array(
            'className' => 'Multiple\Admin\Module',
            'path'      => \Def\PATH::MODULE_ADMIN . '/Module.php')
    ));

    /**
     * リクエスト処理
     */
    $responce = $applicationObj->handle()->getContent();

    /**
     * トランザクションコミット
     */
    if ($di->has('dbManager') && $di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allCommit();
    }

    echo $responce;

} catch (\Exception $e) {
    
    /**
     * トランザクションロールバック
     */
    if ($di->has('dbManager') && $di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allRollback();
    }
    
    echo sprintf('%s: in %s on line %d', $e->getMessage(), $e->getFile(), $e->getLine());
    echo PHP_EOL . ' --- Trace Info --- ' .  PHP_EOL;
    echo $e->getTraceAsString();
}
