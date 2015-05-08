<?php
namespace Multiple\Api;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{
    /**
     * apiモジュールのオートローダ登録
     * @param \Phalcon\DiInterface $di
     */
    public function registerAutoloaders(\Phalcon\DiInterface $di = null) {

        $loaderObj = new \Phalcon\Loader();
        $loaderObj->registerDirs(array(
            __DIR__ . '/controllers/',
            __DIR__ . '/../../models',
            __DIR__ . '/../../library',
        ))->register();

        $loaderObj->register();
    }

    /**
     * apiモジュールのDIコンテナ登録
     * @param \Phalcon\DiInterface $di
     */
    public function registerServices(\Phalcon\DiInterface $di) {

        // apiモジュール用コンフィグをロード
        $config    = include __DIR__ . "/config/config.php";
        $configObj = new \Phalcon\Config($config);

        // DIコンテナ登録
        $serviceObj = new \Takajou\Bootstrap\Service();
        $serviceObj->setDefaultDI($di, $configObj);
    }
}
