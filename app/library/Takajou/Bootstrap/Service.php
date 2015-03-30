<?php
namespace Takajou\Bootstrap;

class Service {

    private $_di;

    /**
     * DIコンテナのデフォルト設定
     */
    public function setDefaultDI(\Phalcon\Config $config) {

        // URL
        $di = new \Phalcon\DI\FactoryDefault();
        $di->set('url', function () use ($config) {
            $url = new UrlResolver();
            $url->setBaseUri($config->services->url->baseUrl);

            return $url;
        }, true);

        // View
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            return $view;
        });

        // DB Manager
        $di->setShared('dbManager', function() use($di, $config) {
            $manager = new \Takajou\Db\Manager($di, $config);
            return $manager;
        });


        // DB
        foreach($config->databases as $clusterType => $databases) {
            foreach($databases as $dbName => $dbSettings) {
                $di->set($dbSettings->diName, function () use($di, $dbSettings) {
                    // DB接続
                    $connection = new \Takajou\Db\Pdo($dbSettings, $di);

                    // PDO処理イベントマネージャの登録
                    if ($dbSettings->isSqlLoging) {
                        $eventsManager = new \Phalcon\Events\Manager();
                        $logger        = new \Phalcon\Logger\Adapter\File($dbSettings->logFile);
                        $eventsManager->attach('db', function($event, $connection) use ($logger) {
                            // SQLクエリログ
                            if ($event->getType() == 'beforeQuery') {
                                 // SQL
                                 $logger->log($connection->getRealSQLStatement(), \Phalcon\Logger::INFO);
                                 // プレースホルダーパラメーター
                                 if ($connection->getSQLVariables()) {
                                     foreach ($connection->getSQLVariables() as $key => $val) {
                                         $logger->log(sprintf(' value [%s] = %s', $key, $val), \Phalcon\Logger::INFO);
                                     }
                                 }
                            }
                        });
                        $connection->setEventsManager($eventsManager);
                    }

                    return $connection;
                });
            }
        }

        $di->set('sqlBuilder', function() {
            return new \Takajou\SqlBuilder\FluentPDO();
        });

        $di->set('config', function () use ($config) {
            return $config;
        });

        $this->_di = $di;
    }

    public function setDI($name, $definition, $shared = false) {
        if(!$di = $this->getDI()) {
            $di = new \Phalcon\DI\FactoryDefault();
        }
        $di->set($name, $definition, $shared);
    }
    
    public function getDI() {
        return $this->_di;
    }
}
