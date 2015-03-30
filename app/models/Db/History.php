<?php
namespace Db;

class History extends \Takajou\Model\Base {
    public function onConstruct() {
        $this->getDI()->get('dbManager')->setConnectDb('history');
        parent::onConstruct();
    }

}
