<?php
namespace Multiple\Admin;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{
    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders($di) {
echo 'test';exit;
        $loaderObj = new \Phalcon\Loader();
        $loaderObj->registerNamespaces(array(
            'Multiple\Api\Controllers' => __DIR__ . '/controllers/',
            'Multiple\Api\Config'      => __DIR__ . '/config/',
            'Multiple\Api\Models'      => __DIR__ . '/models/',
        ))->register();

        $loaderObj->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di) {

        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace("Multiple\Api\Controllers");
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../app/apps/api/views/');
            return $view;
        });
    }
}
