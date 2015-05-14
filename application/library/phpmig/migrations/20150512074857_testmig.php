<?php

use Phpmig\Migration\Migration;

class Testmig extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['gsdb_trun']->query("CREATE TABLE dtb_test (id INTEGER,name TEXT);");
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['gsdb_trun']->query("DROP TABLE IF EXISTS dtb_test;");
    }
}
