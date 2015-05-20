<?php
namespace Util;

/**
 * 環境
 * @author suzuki
 */
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

/**
 * 文字列操作
 * @author suzuki
 *
 */
class String {

    /**
     * 指定したkeyが最後に出現する位置以降の文字列を抽出
     * @param  string $key
     * @return string
     * 例：$string => 「/path/to/file.txt」
     *     $key    => 「/」
     *     return  => 「file.txt」
     */
    public static function afterLastKey($string, $key) {
        return substr($string, strrpos($string, $key) + 1); 
    }


    /**
     * キャメルケース → スネークケース
     */
    public static function camelToSnake($camelString) {
        return strToLower(preg_replace('/([a-z])([A-Z])/', "$1_$2", $camelString));
    }


    /**
     * スネークケース → キャメルケース
     * @param unknown $snakeString
     * @param string $type
     * @return string
     */
    public static function snakeToCamel($snakeString, $type = \Def\STRING::UCC) {

        $tokens = explode('_', $snakeString);
        foreach ($tokens as $index => $val) {
            if($type === \Def\STRING::LCC) {
                if ($index == 0) {
                    $tokens[$index] = lcfirst($val);
                } else {
                    $tokens[$index] = ucfirst($val);
                }
            } elseif($type === \Def\STRING::UCC) {
                $tokens[$index] = ucfirst($val);
            } else {
                
            }
        }

        return implode($tokens);
    }
}

