<?php
namespace Ini;

class Db extends \Ini\Base {
    
    protected static $config = array(
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
                    'isSqlLoging' =>  true,
                    'logPath'     =>  \Def\PATH::LOG,
                    'logFile'     => 'db_gsdb_trun.log'
                ),
            ),
        ),
    );
    
    protected static $devConfig = array(
        'db' => array(
            // clusterMode
            \Takajou\Db\Manager::CLUSTER_MODE_MASTER => array(
                // dbCode
                'gsdb_trun' => array(
                    // descriptor
                    'host'        => 'dev_db01',
                    'username'    => 'gsa',
                    'password'    => 'Pptx3jkla',
                ),
            ),
        ),
    );

}