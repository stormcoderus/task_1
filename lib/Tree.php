<?php
class Tree
{
    private $db                   = null;
    private $groupsArray          = [];
    private $currentGroupId       = null;
    private $currentGroupIdParent = null;
    private $currentGroupName     = "";
    private $lastGroupId          = null;
    private $outputList           = "";
    private $url                  = "";
    private $groupId              = null;

    public function __construct( $groupId )
    {
        $this->db          = new PDO( "mysql:dbname=test_base;host=localhost;charset=UTF8;", "root", "" );
        $this->groupsArray = $this->getGroups();
        $this->groupId     = $groupId;
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

    // Метод обёртки выражения в тег <li>
    private function wrapInTagLi( $expression )
    {
        $liOpen  = "<li>";
        $liClose = "</li>";

        return $liOpen . $expression . $liClose;
    }

    // Метод обёртки выражения в тег <a>
    private function wrapInTagA( $expression, $url )
    {
        $aOpen  = "<a href=$url>";
        $aClose = "</a>";

        return $aOpen . $expression . $aClose;
    }

    /**
     * Метод проверяет, на достижение уровня, который был запрошен
     * в GET параметре groupId
     *
    private function checkBranch()
    {
        if ( $this->groupId == $this->currentGroupId )
        {
            echo 'First';
            $this->makeTree( $this->currentGroupId );
        }
        else if ( $this->groupId == $this->currentGroupIdParent )
        {
            echo 'Second';
            $this->makeTree( $this->currentGroupId );
        } else {
            echo 'Third';
        }

    }

    private function listTree()
    {
        echo "<ul>";

        echo $this->outputList;
        $this->checkBranch();

        echo "</ul>";
    }
    */
    /**
     * Метод вывода дерева
     * @param Integer $id_parent - id-родителя
     */
    public function makeTree( $id_parent )
    {
        // Если группа с $id_parent не найдена, прекратить выполнение
        if ( !isset( $this->groupsArray[ $id_parent ] ) ) return;

        // Перебор массива групп c ключом id_parent
        foreach( $this->groupsArray[ $id_parent ] as $value )
        {
            echo '<script>alert("Making tree");</script>';
            // Инициализация параметров текущей группы
            $this->currentGroupId       = $value[ 'id' ];
            $this->currentGroupIdParent = $value[ 'id_parent' ];
            $this->currentGroupName     = $value[ 'name' ];

            $this->url                  = "index.php?group={$this->currentGroupId}";

            // Составление li - a списка
            $this->outputList           = $this->wrapInTagA( $this->currentGroupName, $this->url );
            $this->outputList           = $this->wrapInTagLi(  $this->outputList );

            // Вывод дерева рекурсией
            echo "<ul>";

            echo $this->outputList;

            if ( $this->groupId == $this->currentGroupId )
            {
                echo 'First';
                $this->makeTree( $this->currentGroupId );
                echo 'First1';
            }
            echo "</ul>";
        }
    }

}
?>