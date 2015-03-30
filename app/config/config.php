<?php
return array(
######
# DB #
######

    'databases' => array(
        \Takajou\Def\Db\ClusterType::MASTER => array(
            'master' => array(
                'diName'      => 'master_master',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'master',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
            'trun' => array(
                'diName'      => 'master_trun',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'trun',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
            'history' => array(
                'diName'      => 'master_history',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'history',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
        ),
        \Takajou\Def\Db\ClusterType::SLAVE => array(
            'master' => array(
                'diName'      => 'slave_master',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'master',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
            'trun' => array(
                'diName'      => 'slave_trun',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'trun',
                'charset'     => 'utf8',
                'isSqlLoging' => true,
                'logFile'     => __DIR__ . '/../../app/log/sql.log',
            ),
            'history' => array(
                'diName'      => 'slave_history',
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '',
                'dbname'      => 'history',
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
