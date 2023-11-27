<?php
 include_once ('connect.php');
 /*$del = "TRUNCATE TABLE `pimRAW`";*/

$count = 0;

 if(isset($_POST["Import"])){

    $filename=$_FILES["file"]["tmp_name"];
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
          $skipHeaders = 0;
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
             $skipHeaders++;
             if ($skipHeaders > 1){
               if (strtolower($getData[26]) == "pink kimberley diamonds"){
                   include 'pk_upload.php';
                   $count++;
               }
               if (strtolower($getData[26]) == "blush pink diamonds"){
                   include 'bp_upload.php';
                   $count++;
               }
               if (strtolower($getData[26]) == "sapphire dreams"){
                   include 'sd_upload.php';
                   $count++;
               }
             }

           }

           fclose($file);
     }
  }

  echo "Total Products Uploaded: ".$count;
  echo "<br><br><a href='index.php'>Return Home</a>";

 ?>
