<?php
namespace Takajou\Bootstrap;

class Service {

    private $di;

    /**
     * DIコンテナのデフォルト設定
     */
    public function setDefaultDI(\Phalcon\DiInterface $di, \Phalcon\Config $configObj) {

#######
# URL #
#######
        if ($configObj->offsetExists('url')) {
            $di->set('url', function () use ($configObj) {
                $urlObj = new UrlResolver();
                $urlObj->setBaseUri($configObj->services->url->baseUri);
    
                return $urlObj;
            }, true);
        }

########
# View #
########
        if ($configObj->offsetExists('view')) {
            $di->set('view', function() {
                $view = new \Phalcon\Mvc\View();
                if (isset($configObj->view->viewsDir)) {
                    $view->setViewsDir($configObj->view->viewsDir);
                }
                return $view;
            });
        }
        
############
# Response #
############
        if ($configObj->offsetExists('response')) {
            $di->set('response', function() use($configObj) {
                $response = new \Phalcon\Http\Response();
                $response->setContentType($configObj->response->contentType->mimeType, $configObj->response->contentType->charset);
                return $response;
            });
        }
        
##########
# DB関連 #
##########
        if ($configObj->offsetExists('db')) {
            // DB Manager
            $di->setShared('dbManager', function() use($configObj) {
                $dbManagerObj = new \Takajou\Db\Manager($configObj->db);
                return $dbManagerObj;
            });
    
            // DB Access
            $di->setShared('dbAccess', function() use($di) {
                $dbManagerObj = $di->getShared('dbManager');
                $dbAccessObj  = new \Takajou\Db\Access($di, $dbManagerObj);
                return $dbAccessObj;
            });
    
            // DB Connection
            foreach($configObj->db as $clusterMode => $databases) {
                foreach($databases as $dbCode => $dbConfigObj) {
                    $di->set($dbConfigObj->diName, function () use($di, $configObj, $dbConfigObj) {
                        // 接続情報
                        $descriptor = array(
                            'host'     => $dbConfigObj->host,
                            'username' => $dbConfigObj->username,
                            'password' => $dbConfigObj->password,
                            'dbname'   => $dbConfigObj->dbname,
                            'charset'  => $dbConfigObj->charset,
                            'port'     => $dbConfigObj->port,
                        );
                        
                        if ( $configObj->offsetExists('pdo_options') === TRUE ) {
                            $descriptor['options'] = $configObj->pdo_options->toArray();
                        }
                        
                        // DB接続
                        $connectionObj = new \Takajou\Db\Adapter\Pdo\Mysql($descriptor);

                        // DBリスナーを生成
                        $dbManagerObj  = $di->getShared('dbManager');
                        $loggerObj     = new \Phalcon\Logger\Adapter\File($dbConfigObj->logPath . '/' . $dbConfigObj->logFile);
                        $dbListenerObj = new \Takajou\Db\Listener($dbManagerObj, $dbConfigObj, $loggerObj);
    
                        // DBコネクションのイベントマネージャを登録
                        $eventsManager = new \Phalcon\Events\Manager();
                        $eventsManager->attach('db', $dbListenerObj);
                        $connectionObj->setEventsManager($eventsManager);
    
                        // 初期DB接続時イベント発火
                        $connectionObj->getEventsManager()->fire('db:afterConnect', $connectionObj);
    
                        return $connectionObj;
                    });
                }
            }
        }

        $di->set('sqlBuilder', function() {
            return new \Takajou\SqlBuilder\FluentPDO();
        });

        /** $di->set('config', function () use ($configObj) {
            return $configObj;
        }); */

##############
# dispatcher #
##############
        if ($configObj->offsetExists('dispatcher')) {

            $di->set('dispatcher', function() use($configObj) {
                $eventsManager = new \Phalcon\Events\Manager();
                $eventsManager->attach('dispatch:beforeException', function($event, $dispatcher, $exception) use($configObj) {

                // 404ページルーティングイベントを登録
                if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                    switch ($exception->getCode()) {
                        case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        // 404ページへ遷移
                        $dispatcher->forward(array(
                            'controller' => $configObj->dispatcher->controller_404,
                            'action'     => $configObj->dispatcher->action_404,
                        ));
                        return false;
                    }
                }
            });

            //Dispatcherの基本動作を設定
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
            });
        }
        
        $this->di = $di;
    }

    public function setDI($name, $definition, $shared = false) {
        if(!$di = $this->getDI()) {
            $di = new \Phalcon\DI\FactoryDefault();
        }
        $di->set($name, $definition, $shared);
    }
    
    public function getDI() {
        return $this->di;
    }
}
