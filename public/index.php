<?php

error_reporting(E_ALL);

try {

    /**
     * Read auto-loader
     */
    $loaderObj = new \Phalcon\Loader();
    $loaderObj->registerDirs(array(
        '../app/controllers/',
        '../app/config/',
        '../app/models/',
        '../app/library/',
    ))->register();

    /**
     * Read the configuration
     */
    $config    = include __DIR__ . "/../app/config/config.php";
    $configObj = new \Phalcon\Config($config);

    /**
     * Read services
     */
    $serviceObj = new \Takajou\Bootstrap\Service();
    $serviceObj->setDefaultDI($configObj);
    $di = $serviceObj->getDI();

    /**
     * Handle the request
     */
    $applicationObj = new \Phalcon\Mvc\Application($di);
    $responce = $applicationObj->handle()->getContent();
    
    if ($di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allCommit();
    }

    echo $responce;

} catch (\Exception $e) {

    if ($di->get('dbManager')->hasBeginedTransaction()) {
        $di->get('dbAccess')->allRollback();
    }
    echo $e->getMessage();
}
