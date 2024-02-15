<?php
    require 'connect.php';

    if(isset($_POST['id'])) $id=$_POST['id'];
    if(isset($_POST['jobnumber'])) $jobnumber=$_POST['jobnumber'];
    if(isset($_POST['user'])) $user=$_POST['user'];
    if(isset($_POST['lognotes'])) $lognotes=$_POST['lognotes'];

    if(!empty($id)){
        $sql = "INSERT into repairs_log (id, job_number, user, notes) VALUES ('$id', '$jobnumber', '$user', '$lognotes') ";
        echo $sql;
        $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    }

    header('Location: https://pim.samsgroup.info/view_repair.php?id='.$id);
    exit();

?>