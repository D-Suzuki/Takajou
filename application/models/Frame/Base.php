<?php

namespace Frame;

class Base
{
    /**
     * Dbオブジェクト
     * @var \Db\...
     */
    private $dbObj;


    /**
     * プライマリーキーの名前
     * @var int
     */
    private $pkName;


    /**
     * プライマリーキーの値
     * @var int
     */
    private $pkValue;


    /**
     * テーブル名
     * @var string
     */
    private $tableName;


    /**
     * コンストラクタ
     * @param  $dbObj
     * @param string $pkValue
     */
    public function __construct($dbObj, $pkValue = null) {

        $this->dbObj     = $dbObj;
        $this->tableName = $dbObj::getTableName();
        $this->pkName    = $dbObj::getPkName();

        if (is_null($pkValue) !== false) {
            $this->preBuild($pkValue);
        }
    }


    /**
     * プライマリーキーからデータを反映
     * @param unknown $pkValue
     */
    private function preBuild($pkValue) {

        $this->pkValue = $pkValue;
        $material      = $this->dbObj->findByPk($pkValue);
        $this->build($material);
    }


    /**
     * データを反映
     * @param array $material
     */
    public function build($material) {

        if ($this->pkValue === null) {
            if ($material[$this->pkName] === null) {
                
            } else {
                $this->pkValue = $material[$this->pkName];                
            }
        }

        foreach ($this->columnMap() as $key) {
            $setter = 'set' . \Util\String::snakeToCamel($key, \Def\STRING::TYPE_UCC);
            $this->$setter($material[$key]);
        }
    }


    /**
     * DBへの保存
     */
    public function save() {

        if (is_null($this->pkValue)) {
            $this->insertSave();
        } else {
            $this->updateSave();
        }
    }


    /**
     * インサート
     */
    private function insertSave() {

        // 変数初期化
        $columnMap  = $this->columnMap();
        $baseQuery  = 'INSERT INTO ' . $this->tableName;
        $columns    = '';
        $values     = '';
        $bindParams = array();
        $firstFlg   = true;

        // インサートクエリ生成
        foreach ($columnMap as $columnkey) {
            $getter = 'get' . \Util\String::snakeToCamel($columnkey, \Def\STRING::TYPE_UCC);
            $value  = $this->$getter();

            if (isset($value) || ($columnkey == 'insert_time' || $columnkey == 'update_time')) {
                if ($firstFlg == false) {
                    $columns .= ', ';
                    $values  .= ', ';
                }
                $columns .= $columnkey;

                if ($columnkey == 'insert_time' || $columnkey == 'update_time') {
                    $values  .= 'NOW()';
                } else {
                    $values  .= '?';
                    $bindParams[] = $value;
                }
                $firstFlg = false;
            }
        }
        $query = $baseQuery . ' (' . $columns . ') VALUES(' . $values . ')';
        
        // クエリ実行
        $this->dbObj->setQuery($query);
        $this->dbObj->setBindParams($bindParams);
        $this->dbObj->exec();

        return $this->dbObj->getLastInsertId();
    }


    /**
     * アップデート
     */
    private function updateSave() {

        // 変数初期化
        $columnMap   = $this->columnMap();
        $baseQuery   = 'UPDATE ' . $this->tableName;
        $setPhrase   = '';
        $wherePhrase = ' WHERE ' . $this->pkName . ' = ?';
        $bindParams  = array();
        $firstFlg    = true;

        // アップデートクエリ生成
        foreach ($columnMap as $columnkey) {
            if ($columnkey === $this->pkName) {
                continue;
            }
            $getter = 'get' . \Util\String::snakeToCamel($columnkey, \Def\STRING::TYPE_UCC);
            $value  = $this->$getter();

            if (isset($value) || $columnkey == 'update_time') {
                if ($firstFlg === true) {
                    $setPhrase .= ' SET ';
                } else {
                    $setPhrase .= ', ';
                }
                if ($columnkey === 'update_time') {
                    $setPhrase .= $columnkey . ' = NOW()';
                } else {
                    $setPhrase .= $columnkey . ' = ?';
                    $bindParams[] = $value;
                }
                $firstFlg = false;
            }
        }
        $query = $baseQuery . $setPhrase . $wherePhrase;
        $bindParams[] = $this->pkValue;

        // クエリ実行
        $this->dbObj->setQuery($query);
        $this->dbObj->setBindParams($bindParams);
        $this->dbObj->exec();
    }
}