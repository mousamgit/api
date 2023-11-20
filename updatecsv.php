<?php
 include_once ('connect.php');
 /*$del = "TRUNCATE TABLE `pimRAW`";
 mysqli_query( $con, $del );*/

 echo "<div style='width:500px; margin:0 auto; border:1px solid #000; padding:20px;'>";

 if(isset($_POST["Import"])){

    $filename=$_FILES["file"]["tmp_name"];
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
          $skipHeaders = 0;
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
             if ($skipHeaders == 0)
             {
               $header .= $getData[1];
             }
             $skipHeaders++;
             if ($skipHeaders > 1){
               $sql = "UPDATE pim set ".$header." = '".$getData[1]."' WHERE sku = '".$getData[0]."'";
               $result = mysqli_query($con, $sql);

               echo 'Updated '.$header.' of '.$getData[0];
               echo '<ul><li>'.$getData[1].'</li></ul>';
             }

           }

           fclose($file);
     }
  }

  echo "</div>";
 ?>
