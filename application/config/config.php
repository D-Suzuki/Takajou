<?php
return array(
######
# DB #
######
    'db' => array(
        \Takajou\Db\Manager::CLUSTER_MODE_MASTER => array(
            'gsdb_trun' => array(
                'diName'      => 'gsdb_trun',
                'adapter'     => 'Mysql',
                'host'        => 'db01',
                'username'    => 'gsa',
                'password'    => 'Pptx3jkla',
                'dbName'      => 'gsdb_trun',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
        ),
    ),

#######
# url #
#######
    'url' => array(
        'baseUri'        => '/',
    ),
);
