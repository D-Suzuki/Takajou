<?php

error_reporting(E_ALL);

try {

    /**
     * Read auto-loader
     */
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
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
    $service = new \Takajou\Bootstrap\Service();
    $service->setDefaultDI($configObj);
    $di = $service->getDI();

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    $responce = $application->handle()->getContent();

    if ($di->get('dbManager')->hasStartedTransactions()) {
        $di->get('dbManager')->allCommit();
    }

    echo $responce;

} catch (\Exception $e) {

    if ($di->get('dbManager')->hasStartedTransactions()) {
        $di->get('dbManager')->allRollback();
    }
    echo $e->getMessage();
}
