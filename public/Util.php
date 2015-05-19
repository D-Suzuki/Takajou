<?php
namespace Util;

class Env {

    /**
     * 環境タイプ取得
     * @return string
     * [ 'prod' / 'dev' ]
     */
    public static function getType() {

        // ホスト名取得
        list($identifier) = explode( '.', exec( 'hostname' ) );
        if ( strcmp( $identifier, 'dev' ) == 0 ) {
            return \Def\ENV::DEVELOP;
        } else {
            return \Def\ENV::PRODUCT;
        }
    }

}
