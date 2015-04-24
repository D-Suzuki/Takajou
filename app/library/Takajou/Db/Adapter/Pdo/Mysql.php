<?php
namespace Takajou\Db\Adapter\Pdo;

class Mysql extends \Phalcon\Db\Adapter\Pdo\Mysql {

    /**
     * コンストラクタ
     * @param \Phalcon\Config $dbConfig
     */
    public function __construct(\Phalcon\Config $dbConfig) {
        if (!$dbConfig) {
//TODO:throw exception
        }
        $descriptor = array(
            'host'     => $dbConfig->host,
            'username' => $dbConfig->username,
            'password' => $dbConfig->password,
            'dbname'   => $dbConfig->dbname,
            "charset"  => $dbConfig->charset,
        );
        parent::__construct($descriptor);
    }
    
    /**
     * 接続
     * @param unknown $descriptor
     * イベント発火のためconnectメソッドをインターセプト
     */
    public function connect($descriptor = null) {
        if ($descriptor == null) {
            $descriptor = $this->getDescriptor();
        }
        parent::connect($descriptor);
        // イベント発火
        if ($this->getEventsManager()) {
            $this->getEventsManager()->fire("db:afterConnect", $this);
        }
    }
    
    /**
     * 切断
     * イベント発火のためcloseメソッドをインターセプト
     */
    public function close() {
        // イベント発火
        if ($this->getEventsManager()) {
            $this->getEventsManager()->fire("db:beforeDisconnect", $this);
        }
        parent::close();
    }
}
