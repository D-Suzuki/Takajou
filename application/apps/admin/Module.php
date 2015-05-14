<?php
namespace Multiple\Admin;

class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{
    /**
     * adminモジュールのオートローダ登録
     * @param \Phalcon\DiInterface $di
     */
    public function registerAutoloaders($di) {

        $loaderObj = new \Phalcon\Loader();
        $loaderObj->registerDirs(array(
            \Def\PATH::MODULE_ADMIN . '/controllers/',
            \Def\PATH::CONFIG,
            \Def\PATH::MODELS,
            \Def\PATH::LIBRARY,
        ))->register();

        $loaderObj->register();
    }

    /**
     * adminモジュールのDIコンテナ登録
     * @param \Phalcon\DiInterface $di
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
