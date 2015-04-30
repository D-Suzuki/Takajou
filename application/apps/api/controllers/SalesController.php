<?php

class SalesController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction() {

        $dailyUserDataObj = \Db\Factory::createInstance('trun', 'daily_user_data');
        $sales = $dailyUserDataObj->getSales();
        
        return $sales;
    }

/*`
    public function afterExecuteRoute() {

    }
*/
}
