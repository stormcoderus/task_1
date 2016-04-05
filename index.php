<?php
require_once 'lib/Database.php';
require_once 'lib/Tree.php';
require_once 'lib/Groups.php';
require_once 'lib/Products.php';
require_once 'lib/Wraps.php';

$groupsIds = isset($_GET['groupsIds']) ? $_GET['groupsIds'] : '';

$wraps = new Wraps();
$groups = new Groups($groupsIds, new Database(), new Wraps());
$products = new Products($groupsIds, new Database(), $groups);
$tree = new Tree($groups, $products);
?>
<!DOCTYPE>
<html>
<head>
    <title>Task Tree</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<div id="content">
    <a href="index.php">Все товары</a>
    <?php
    $tree->renderGroupsTree();
    ?>
</div>
<aside>
    <?php
    $tree->renderProductsTree();
    ?>
</aside>

</body>
</html>
