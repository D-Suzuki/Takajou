<?php

class UsersController extends \Takajou\Controller\Base
{

    public function beforeExecuteRoute() {

    }

    public function indexAction()
    {
        $this->getService('dbAccess')->createSharedConnection('gsdb_trun', $isBegin = true);
        var_dump($this->getService('dbManager')->getConnectionPool());
        
        $this->getService('dbAccess')->closeConnection(1);
        //$this->getService('dbAccess')->reConnect(1);
        
        echo '<pre>';
        var_dump($this->getService('dbManager')->getConnectionPool());exit;
        var_dump($this->getService('dbManager')->getBeginedConnectionIds());exit;
        
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
