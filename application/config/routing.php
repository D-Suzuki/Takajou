<?php
return function() {
    $router = new \Phalcon\Mvc\Router();
    $router->setDefaultModule("api");

    $router->add('/:module/', array(
        'module' => '1',
        'action' => 'index',
        'params' => 'index'
    ));
    $router->add('/:module/:controller/', array(
        'module'     => '1',
        'controller' => '2',
        'action'     => 'index'
    ));
    $router->add('/:module/:controller/:action/:params', array(
        'module'     => 1,
        'controller' => 2,
        'action'     => 3,
        'params'     => 4
    ));
    
    return $router;
};
