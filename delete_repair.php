<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';

    $id = $_GET['id'];

   
    if(!empty($id)){
        $sql = "DELETE FROM repairs where id='$id'";      
        $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    }

   header('Location: https://pim.samsgroup.info/repairs.php');
   exit();


?>