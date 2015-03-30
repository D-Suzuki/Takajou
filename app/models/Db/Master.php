<?php
namespace Db;

class Master extends \Takajou\Model\Base {
    public function onConstruct() {
        $this->getDI()->get('dbManager')->setConnectDb('master');
        parent::onConstruct();
    }
}
