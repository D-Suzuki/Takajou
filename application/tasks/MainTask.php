<?php

class MainTask extends \Phalcon\CLI\Task
{

    public function mainAction() {

        $salesModelObj = new \Model\Sales();
        $recentlySales = $salesModelObj->getRecentlySales();
        var_dump($recentlySales);exit;
    }
}