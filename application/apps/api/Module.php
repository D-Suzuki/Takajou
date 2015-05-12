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
            __DIR__ . '/../../config/',
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
        $configObj      = new \Phalcon\Config();
        $configObj->merge(new \Phalcon\Config(\Ini\Db::load()));
        $configObj->merge(new \Phalcon\Config(\Ini\Url::load()));
        $configObj->merge(new \Phalcon\Config(\Ini\View::load()));
        $configObj->merge(new \Phalcon\Config(\Ini\Response::load()));

        // DIコンテナ登録
        $serviceObj = new \Takajou\Bootstrap\Service();
        $serviceObj->setDefaultDI($di, $configObj);
    }
}
