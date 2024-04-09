<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';
    $columns = "";

    if(isset($_POST['product_title'])) $columns .= "product_title,";
    if(isset($_POST['quantities'])) $columns .= "quantities,";
    if(isset($_POST['retail'])) $columns .= "retail,";
    if(isset($_POST['specifications'])) $columns .= "specifications,";
    if(isset($_POST['tags'])) $columns .= "tags,";
    if(isset($_POST['wholesale'])) $columns .= "wholesale,";
    if(isset($_POST['image1'])) $columns .= "image1,";
    if(isset($_POST['user'])) $user=$_POST['user'];
    if(isset($_POST['searchterm'])) $searchterm=$_POST['searchterm'];
    $columns = rtrim($columns,',');
    echo $columns;

    if(!empty($columns)){
        $sql = "UPDATE users SET searchcolumns='$columns' WHERE username='$user'";
        $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    }

    header('Location: https://pim.samsgroup.info/search.php?var='.$searchterm);
    exit();

?>