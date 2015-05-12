<?php
namespace Ini;

class Base {

    /**
     * 設定配列ロード
     * @return array
     */
    public static function load() {

        // 呼出元クラスインスタンス
        $class = get_called_class();

        // 開発環境の場合は$devConfigを$configにマージ
        if (\Utility::getEnv() == \Def::ENV_TYPE_DEVELOP && isset($class::$devConfig)) {
            self::mergeConfig($class::$devConfig, $class::$config);
        }
        return $class::$config;
    }

    /**
     * 再起的に設定配列をマージ
     * 多重配列もマージ可能
     * @param array $fromArray
     * @param array $toArray
     */
    public static function mergeConfig($fromArray, &$toArray) {

        foreach ($fromArray as $key => $val) {
            // 本番設定値を入れ忘れないためのチェック
            if (!isset($toArray[$key])) {
                $class = get_called_class();
                throw new \Phalcon\Config\Exception(sprintf('\\%s::$configに存在しない配列をマージしようとしています', $class));
            }
            // $valが配列の場合は再起処理
            if(is_array($val)) {
                self::mergeConfig($fromArray[$key], $toArray[$key]);
            // $valが値の場合は上書き
            } else {
                $toArray[$key] = $val;
            }
        }
    }
}