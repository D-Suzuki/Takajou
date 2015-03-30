<?php
namespace Db;

class Trun extends \Takajou\Model\Base {
    public function onConstruct() {
        $this->getDI()->get('dbManager')->setConnectDb('trun');
        parent::onConstruct();
    }

}
