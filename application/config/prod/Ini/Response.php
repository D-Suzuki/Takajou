<?php
namespace Ini;

class Response {

    public static function load() {
        return array(
            'response' => array(
                'contentType'    => array(
                    'mimeType'       => 'application/json',
                    'charset'        => 'UTF-8',
                ),
            ),
        );
    }
}