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
        if ($configObj->url) {
            $di->set('url', function () use ($configObj) {
                $urlObj = new UrlResolver();
                $urlObj->setBaseUri($configObj->services->url->baseUri);
    
                return $urlObj;
            }, true);
        }

########
# View #
########
        if ($configObj->view) {
            $di->set('view', function() {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir($configObj->view->viewsDir);
                return $view;
            });
        }
        
############
# Response #
############
        if ($configObj->response) {
            $di->set('response', function() use($configObj) {
                $response = new \Phalcon\Http\Response();
                $response->setContentType($configObj->response->contentType->mimeType, $configObj->response->contentType->charset);
                return $response;
            });
        }
        
##########
# DB関連 #
##########
        // DB Manager
        $di->setShared('dbManager', function() use($configObj) {
            $dbManagerObj = new \Takajou\Db\Manager($configObj->databases);
            return $dbManagerObj;
        });

        // DB Access
        $di->setShared('dbAccess', function() use($di) {
            $dbManagerObj = $di->getShared('dbManager');
            $dbAccessObj  = new \Takajou\Db\Access($dbManagerObj);
            return $dbAccessObj;
        });

        // DB Connection
        foreach($configObj->databases as $clusterMode => $databases) {
            foreach($databases as $dbName => $dbConfigObj) {
                $di->set($dbConfigObj->diName, function () use($di, $dbConfigObj) {
                    // 接続情報
                    $descriptor = array(
                        'host'     => $dbConfigObj->host,
                        'username' => $dbConfigObj->username,
                        'password' => $dbConfigObj->password,
                        'dbname'   => $dbConfigObj->dbName,
                        "charset"  => $dbConfigObj->charset,
                    );
                    
                    // DB接続
                    $connectionObj = new \Takajou\Db\Adapter\Pdo\Mysql($descriptor);

                    // DBリスナーを生成
                    $dbManagerObj  = $di->getShared('dbManager');
                    $loggerObj     = new \Phalcon\Logger\Adapter\File($dbConfigObj->logFile);
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

        $di->set('sqlBuilder', function() {
            return new \Takajou\SqlBuilder\FluentPDO();
        });

        /** $di->set('config', function () use ($configObj) {
            return $configObj;
        }); */

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
