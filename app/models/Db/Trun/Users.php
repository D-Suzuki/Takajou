<?php
namespace Db\Trun;

class Users extends \Db\Trun {

    public function getUsers() {
        $connection = $this->getConnection();
        return $connection->query('SELECT * FROM users')->fetchAll();
    }

    public function getUsersById($id)
    {
        $connection = $this->getConnection();
        $query     = 'SELECT * FROM users WHERE id = :id';
        $params    = array('id' => $id);
        $statement = $connection->prepare($query);
        return $connection->executePrepared($statement, $params, array())->fetchAll();
    }

    public function updateNameById($id, $name)
    {
        $connection = $this->getConnection();
        $query      = 'UPDATE users SET name = ? WHERE id = ?';
        $params     = array($name, $id);
        $connection->execute($query, $params);
    }

    public function sideci_test()
    {

    }
}
