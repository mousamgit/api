<?php

    $img = $_GET['id'];

   
    if(!empty($img)){
        unlink("temp-images/".$img);
    }

   header('Location: https://pim.samsgroup.info/temp_images.php');
   exit();


?>