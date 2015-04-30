<?php
return array(
######
# DB #
######
    'databases' => array(
        \Takajou\Def\Db\ClusterMode::NONE => array(
            'gsdb_trun' => array(
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

#######
# url #
#######
    'url' => array(
        'baseUri'        => '/',
    ),
    
########
# View #
########
    'view' => array(
        'viewsDir'       => '',
    ),
    
############
# response #
############
    'response' => array(
        'contentType'    => array(
            'mimeType'       => 'application/json',
            'charset'        => 'UTF-8',
        ),
    ),
);
