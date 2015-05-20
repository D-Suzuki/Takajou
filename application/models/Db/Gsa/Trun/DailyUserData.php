<?php
namespace Db\Gsa\Trun;

class DailyUserData extends \Db\Gsa\Trun {

    const PK = 'test';

    public function getSalesData() {

        $fromDate = date('Y-m-d', strtotime('-3 month'));
        $this->setQuery('SELECT * FROM ' . $this->tableName . ' WHERE aggregate_date >= ?');
        $this->setBindParams(array($fromDate));

        return $this->select();
    }
}
