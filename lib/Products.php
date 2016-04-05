<?php

class Products
{
    private $database = null;
    private $parentId = null;
    private $groups = null;
    private $groupsArray = [];
    private $groupsIds = [];

    public function __construct($groupsIds, Database $database, Groups $groups)
    {
        $this->database = $database;
        $this->groups = $groups;
        $this->parentId = array_pop($groups->groupsIds);
        $this->groupsArray = $this->getGroupsArray();
    }

    public function render()
    {
        $this->renderProducts();
    }

    private function getGroupsArray()
    {
        $result = $this->database->getGroups();
        $return = [];

        foreach ($result as $value) {
            $return[$value['id_parent']][] = $value;
        }

        return $return;
    }

    public function getProducts($id)
    {
        if (!in_array($id, $this->groupsIds)) {
            array_unshift($this->groupsIds, $id);
        }

        if (!isset($this->groupsArray[$id])) {
            return;
        }

        foreach ($this->groupsArray[$id] as $value) {
            $this->groupsIds[] = $value['id'];
            $this->getProducts($value['id']);
        }
    }

    public function renderProducts()
    {
        $this->getProducts($this->parentId);

        $groups = implode(',', $this->groupsIds);
        $products = $this->database->getProducts($groups);

        foreach ($products as $value) {
            echo $value['name'].'<br>';
        }
    }
}
