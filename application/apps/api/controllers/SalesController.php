<?php

class SalesController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction() {

        $salesModelObj = new \Model\Sales();
        $recentlySales = $salesModelObj->getRecentlySales();

        return $recentlySales;
    }
}
