<?php
class Tree {

    private $_db = null;
    private $_group_arr = array();
    public  $groupId;

    public function __construct() {
        //Подключаемся к базе данных, и записываем подключение в переменную _db
        $this->_db = new PDO("mysql:dbname=test_base;host=localhost;charset=UTF8;", "root", "");
        //В переменную $_category_arr записываем все категории (см. ниже)
        $this->_group_arr = $this->_getGroup();
    }

    /**
     * Метод читает из таблицы category все сточки, и
     * возвращает двумерный массив, в котором первый ключ - id - родителя
     * категории (parent_id)
     * @return Array
     */
    private function _getGroup() {
        $query = $this->_db->prepare("SELECT * FROM `groups`"); //Готовим запрос
        $query->execute(); //Выполняем запрос
        //Читаем все строчки и записываем в переменную $result
        $result = $query->fetchAll();
        //Перелапачиваем массив (делаем из одномерного массива - двумерный, в котором
        //первый ключ - parent_id)
        $return = array();
        foreach ($result as $value) { //Обходим массив
            $return[$value['id_parent']][] = $value;
        }
        //print_r($return);
        return $return;
    }

    /**
     * Вывод дерева
     * @param Integer $parent_id - id-родителя
     * @param Integer $level - уровень вложености
     */
    public function outTree($id_parent) {
        if (isset($this->_group_arr[$id_parent])) { //Если категория с таким parent_id существует}
            //print_r($this->_group_arr[$id_parent]);
            foreach ($this->_group_arr[$id_parent] as $value) { //Обходим ее
                echo "<ul>";
                echo "<li><a href='index.php?group=" . $value['id'] . "'>" . $value['name'] . "</li>";
                // (array_key_exists(self::$groupId, $this->_group_arr) ) ||
                if ( $this->groupId == $value['id'] ) {
                    $this->outTree($value['id']);
                } else {
                    echo "</ul>";
                    continue;
                }
                echo "</ul>";
            }
        }
    }

}
?>