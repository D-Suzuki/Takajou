<?php
namespace Ini;

class Response extends \Ini\Base {

    protected static $config = array(
        'response' => array(
            'contentType'    => array(
                'mimeType'       => 'application/json',
                'charset'        => 'UTF-8',
            ),
        ),
    ); 
}