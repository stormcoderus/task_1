<?php
class Tree
{
    private $db = null;
    private $groupsArray = [];
    private $url = "";
    private $currentGroupId = null;
    private $currentGroupIdParent = null;
    private $currentGroupName = "";
    private $outputList = "";

    public  $groupId;
    public  $row = [];

    public function __construct($groupId)
    {
        $this->db         = new PDO("mysql:dbname=test_base;host=localhost;charset=UTF8;", "root", "");
        $this->groupsArray = $this->getGroups();
        $this->groupId    = $groupId;
        $this->url        = "index.php?group=" . $this->groupId;
    }

    /**
     * Метод читает из таблицы groups все сточки, и
     * возвращает двумерный массив, в котором первый ключ - id - родителя
     * категории (id_parent)
     * @return Array
     */
    private function getGroups()
    {
        $query = $this->db->prepare( "SELECT * FROM `groups`" );
        $query->execute();
        $result = $query->fetchAll();
        $return = [];

        foreach( $result as $value ) {
            $return[ $value[ 'id_parent' ] ][] = $value;
        }

        return $return;
    }

    private function wrapInTagUl( $expression )
    {
        $ulOpen  = "<ul>";
        $ulClose = "</ul>";

        return $ulOpen . $expression . $ulClose;
    }

    private function wrapInTagLi( $expression )
    {
        $liOpen  = "<li>";
        $liClose = "</li>";

        return $liOpen . $expression . $liClose;
    }

    private function wrapInTagA( $expression, $url )
    {
        $aOpen  = "<a href=" . $url . ">";
        $aClose = "</a>";

        return $aOpen . $expression . $aClose;
    }

    private function checkBranch()
    {
        if ( $this->currentGroupId == $this->groupId )
        {
            $this->makeTree( $this->currentGroupId );
        }
    }

    public function makeTree($id_parent)
    {
        if ( !isset( $this->groupsArray[ $id_parent ] ) ) return;

        foreach( $this->groupsArray[ $id_parent ] as $value )
        {
            $this->currentGroupId       = $value[ 'id' ];
            $this->currentGroupIdParent = $value[ 'id_parent' ];
            $this->currentGroupName     = $value[ 'name' ];

            $this->outputList = $this->wrapInTagA( $this->currentGroupName , $this->url );
            $this->outputList = $this->wrapInTagLi(  $this->outputList );

            echo "<ul>";
            echo $this->outputList;
            $this->makeTree( $this->currentGroupId );
            echo "</ul>";
        }
    }

}
?>