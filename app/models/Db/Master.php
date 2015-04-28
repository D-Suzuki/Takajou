<?php
namespace Db;

class Master extends \Takajou\Db\Base {

    public function getConnectDbName() {
        return 'master';
    }
}
