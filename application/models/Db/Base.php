<?php
namespace Db;

abstract class Base extends \Takajou\Db\Base {

##############
# DB情報取得 #
##############
    /**
     * テーブル名取得
     * @return string
     */
    public static function getTableName() {

        $tableClass = get_called_class();
        $className  = \Util\String::afterLastKey($tableClass, '\\');
        $tableName  = \Util\String::camelToSnake($className);
    
        return $tableName;
    }


    /**
     * データベース名取得
     * @return string
     */
    public static function getDbName() {

        $dbClass   = get_parent_class(get_called_class());
        $className = \Util\String::afterLastKey($dbClass, '\\');
        $dbName    = \Util\String::camelToSnake($className);

        return $dbName;
    }


    /**
     * プライマリキー取得
     * @return Ambigous <NULL, mixed>
     */
    public static function getPkName() {

        $primaryKeyName = null;
        $tableClass     = get_called_class();
        $pkConstName    = $tableClass . '::PK';

        if (defined($pkConstName)) {
            $primaryKeyName = constant($pkConstName);
        }

        return $primaryKeyName;
    }

##########
# select #
##########
    /**
     * プライマリーキーによるselect
     * @param  int $pkValue
     * @return array
     */
    public function findByPk($pkValue) {

        $tableName = self::getTableName();
        $pkName    = self::getPkName();
        $query = <<<EOT
  SELECT * 
    FROM {$tableName}
   WHERE {$pkName} = ?
EOT;

        $this->setQuery($query);
        $this->setBindParams(array($pkValue));
        return $this->selectRow();
    }
}
