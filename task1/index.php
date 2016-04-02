<!DOCTYPE>
<html>
<head>
    <title>Test work</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php
require_once 'lib/Tree.php';

session_start();
if (!isset($_SESSION['group'])) {
    $_SESSION['group'] = 0;
}
$_SESSION['group'] = $_GET['group'];
$groupId = $_GET['group'];
echo 'session= '.$_SESSION['group'].'<br>';
echo 'get= '.$_GET['group'].'<br>';
?>


<div id="content">
    <a href="index.php?group=0">Все товары</a>
    <?php
    $tree = new Tree();
    $tree->groupId = $_GET['group'];

    $tree->outTree(0); //Выводим дерево
    echo('$tree->groupId= '.$_GET['group'].'<br>');
    ?>
</div>

</body>
</html>
