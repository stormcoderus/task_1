<?php

class Database
{
    public $db = null;

    private $dbname = 'test_base';
    private $host = 'localhost';
    private $charset = 'utf8';
    private $user = 'root';
    private $password = '';

    public function __construct()
    {
        $this->db = new PDO(
            "mysql:dbname={$this->dbname};
             host={$this->host};
             charset={$this->charset};",
            "{$this->user}",
            "{$this->password}"
        );
    }

    public function getGroups($groupsIds = null)
    {
        if (isset($groupsIds)) {
            $query = $this->db->prepare('SELECT * FROM `groups` WHERE `id_parent` IN('.$groupsIds.')
                                         ORDER BY `id_parent` ASC, `id` ASC;');
            $query->execute();
            $result = $query->fetchAll();
        } else {
            $query = $this->db->prepare('SELECT * FROM `groups`');
            $query->execute();
            $result = $query->fetchAll();
        }

        return $result;
    }

    public function getProducts($groups)
    {
        $query = $this->db->prepare('SELECT * FROM `products` WHERE `id_group` IN('.$groups.') ORDER BY `id`');
        $query->execute();
        $products = $query->fetchAll();

        return $products;
    }
}
