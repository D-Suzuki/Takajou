<?php
namespace Ini;

class Db {

    public static function load() {
        return array(
            'db' => array(
                // clusterMode
                \Takajou\Db\Manager::CLUSTER_MODE_MASTER => array(
                    // dbCode
                    'gsdb_trun' => array(
                        // descriptor
                        'diName'      => 'gsdb_trun',
                        'adapter'     => 'Mysql',
                        'host'        => 'db01',
                        'username'    => 'gsa',
                        'password'    => 'Pptx3jkla',
                        'dbName'      => 'gsdb_trun',
                        'charset'     => 'utf8',
                        'isSqlLoging' => true,
                        'logFile'     => __DIR__ . '/../../../log/sql.log',
                    ),
                ),
            ),
        );
    }
}