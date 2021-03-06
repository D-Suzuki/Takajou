<?php

class IndexController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction()
    {
        $this->getService('dbAccess')->createSharedConnection('slave_trun', $isBegin = true);
        $usersObj = \Db\Factory::createInstance('\Db\Trun\Users');
        $users = $usersObj->getUsers();

        return $users;
    }

    public function updateAction()
    {
        $id = 1;
        $this->getService('dbManager')->MasterModeOn();
        $this->getService('dbManager')->autoBeginOn();
        $usersObj = new \Db\Trun\Users();
        $usersObj->updateNameById($id, 'tramsaction55555');
        return $usersObj->getUsersById(1);
    }
/*`
    public function afterExecuteRoute() {

    }
*/
}
