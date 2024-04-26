


<?php
    include '../functions.php';
    loginChecking(array('admin', 'sales'));
    $username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>

    <title>Appro List</title>
</head>
<body>

<div id="list">
<!--    remember passing value below in primary-table,key_name,filter_table,column_table is compulsary and should match exactly with tables otherwise will render blank-->
    <list :urlsku="'test'" :primary_table="'appro'" :key_name="'id'" :show_filter_button="true"></list>
</div>
<script type="module" src="../crud/list.js" defer></script>
</body>
</html>

