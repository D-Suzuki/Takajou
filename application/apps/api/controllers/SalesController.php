<?php

class SalesController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction() {

        $this->di->getDbAccess()->createSharedConnection('gsdb_trun', $isbegin = true);
        $salesModelObj = new \Model\Sales();
        $recentlySales = $salesModelObj->getRecentlySales();

        return $recentlySales;
    }

}
