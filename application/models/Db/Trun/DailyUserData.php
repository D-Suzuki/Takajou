<?php
namespace Db\Trun;

class DailyUserData extends \Db\Trun {
    
    private $tableName = 'daily_user_data';

    public function getSales() {

        $this->setQuery('SELECT * FROM ' . $this->tableName . ' WHERE aggregate_date = "2015-04-26"');
        
        return $this->exec();
    }
}
