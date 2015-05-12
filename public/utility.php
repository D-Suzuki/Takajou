<?php
class Utility {

    /**
     * 環境タイプ取得
     * @return string
     * [ 'prod' / 'dev' ]
     */
    public static function getEnv() {

        // ホスト名取得
        list($identifier) = explode( '.', exec( 'hostname' ) );
        if ( strcmp( $identifier, 'dev' ) == 0 ) {
            return \Def::ENV_TYPE_DEVELOP;
        } else {
            return \Def::ENV_TYPE_PRODUCT;
        }
    }
}