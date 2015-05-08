<?php
namespace Model;

class Sales
{
    public function getRecentlySales() {
        $dailyUserDataObj = \Db\Factory::createInstance('gsa', 'trun', 'daily_user_data');
        $recentlySales = $dailyUserDataObj->getSalesData();

        return $recentlySales;
    }
}