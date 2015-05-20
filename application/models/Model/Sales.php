<?php
namespace Model;

class Sales
{
    public function saveDailyUserData($params) {

        /* @var $dailyUserDataFrameObj \Frame\DailyUserData */
        $dailyUserDataFrameObj = \Frame\Factory::createInstance('daily_user_data');
        $dailyUserDataFrameObj->setAggregateDate('2015-06-04');
        $dailyUserDataFrameObj->setGameId('001');
        $dailyUserDataFrameObj->setAmount('13000000');
        $dailyUserDataFrameObj->setMobageUserId('2238');
        $dailyUserDataFrameObj->save();

    }

    public function getRecentlySales() {
        
        //$pkValue = 2;
        /* @var $dailyUserDataDbObj \Db\Gsa\Trun\DailyUserData */
        //$adminUserDataFrameObj = \Frame\Factory::createInstance('admin_user_data', $pkValue);
        //$adminUserDataFrameObj->setPassword('ggg');
        //$adminUserDataFrameObj->save();

        $dailyUserDataDbObj = \Db\Factory::createInstance('gsa', 'trun', 'daily_user_data');
        $recentlySales      = $dailyUserDataDbObj->getSalesData();

        return $recentlySales;
    }
}