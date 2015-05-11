<?php
namespace Ini;

class Db {

    public static function load() {
        return array(
            'db' => array(
                // clusterMode
                \Takajou\Def\Db\ClusterMode::NONE => array(
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
                        'isSqlLoging' =>  true,
                        'logFile'     => __DIR__ . '/../../../log/sql.log',
                    ),
                ),
            ),
        );
    }
}