<?php
 $startScriptTime=microtime(TRUE);
 include_once ('connect.php');
 /*$del = "TRUNCATE TABLE `pimRAW`";*/

$count = 0;

$excludedsku = array("28-95G-B'LET","53-29G-B'LET","72002-B'LET","PKM-PEDSB0101","PKM-RDDPB0601","POUCHES B'LET","SDM-RDSPPT001");


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
               if ( in_array(strtolower($brand),$excludedsku) ) { }
               else{
                 if (strtolower($getData[26]) != ""){
                   include 'item_upload.php';
                   $count++;
                 }
               }
             }

           }

           fclose($file);
     }
  }
  echo "Total Products Uploaded: ".$count."<br><br>";
  $endScriptTime=microtime(TRUE);
  $totalScriptTime=$endScriptTime-$startScriptTime;
  echo '<br>Processed in: '.number_format($totalScriptTime, 4).' seconds';
  echo "<br><br><a href='index.php'>Return Home</a>";

 ?>
