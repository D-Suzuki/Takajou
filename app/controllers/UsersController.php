<?php

class UsersController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction()
    {
        $primaryKey = 1;
        $this->getService('dbManager')->slaveModeOn();
        $usersObj = \Db\Factory::getInstance('Trun\Users', $primaryKey);
        $users = $usersObj->getRecords();
        return $users;
    }

    public function updateAction()
    {
        $id = 1;
        $this->getService('dbManager')->MasterModeOn();
        $usersObj = \Db\Factory::getInstanceWithBegin('Trun\Users');
        $usersObj->updateNameById($id, 'beginTest3');
        $usersObj->commitTransaction();

        return $usersObj->getUsersById($id);
    }
/*`
    public function afterExecuteRoute() {

    }
*/
}    
