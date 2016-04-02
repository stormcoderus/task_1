<!DOCTYPE>
<html>
<head>
    <title>Task 1</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php
if ( !isset( $_GET['group'] ) ) {
    $_GET['group'] = 0;
}
$groupId = $_GET['group'];

require_once 'lib/Tree.php';
$tree = new Tree($groupId);
?>

<div id="content">
    <a href="index.php?group=0">Все товары</a>
    <ul>
        <?php
        $tree->makeTree( 0 ); // 0 -id начального родителя
        ?>
    </ul>
</div>

</body>
</html>
