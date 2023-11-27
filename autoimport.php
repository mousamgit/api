<?php
 include_once ('connect.php');

 $filename = "APP_WebA2022.csv";
 $filedirect = $_SERVER['DOCUMENT_ROOT'].'/datavault/SYNC-2-PKweb-APP/CSV/'.$filename;
 $count = 0;


 if( file_exists($filedirect) )
 {
    $file = fopen($filedirect, "r");
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

 echo "Total Products Uploaded: ".$count;
 echo "<br><br><a href='index.php'>Return Home</a>";

?>
