<?php

class Tree
{
    private $groups = null;
    private $products = null;

    public function __construct(Groups $groups, Products $products)//gropsIds = 0,1,3
    {
        $this->groups = $groups;
        $this->products = $products;
    }

    public function renderGroupsTree()
    {
        $this->groups->render();
    }

    public function renderProductsTree()
    {
        $this->products->render();
    }
}
