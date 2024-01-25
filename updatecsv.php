<?php
  include 'login_checking.php';
  include 'functions.php';
?>
<head>
<?php include 'header.php'; ?>
</head>
<?php include 'topbar.php'; ?>
<?php
 include ('connect.php');
 /*$del = "TRUNCATE TABLE `pimRAW`";
 mysqli_query( $con, $del );*/

 echo "<center><div style='width:70%; padding:20px;'>";

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
               $header = $getData;
               $header = str_replace(" ","_",$header);
               $header = array_map('mb_strtolower', $header);
               $header = array_map('trim', $header);
               $header[0] = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $header[0]);

               if ($header[0] != "sku")
               {
                echo "Error! First Column must be sku<br><a href='javascript:history.go(-1)'>Go Back</a>";
                break;
               }
             }
             $skipHeaders++;
             if ($skipHeaders > 1){
               $values = $getData;
               $arrayLength = count($values);

               ?>

              <table style="border:1px solid #000; ">
              <tr><td width=20% style="border:1px solid #000; padding:10px; font-weight:700;">SKU</td><td width=20% style="border:1px solid #000; padding:10px; font-weight:700;">Field</td><td style="border:1px solid #000; padding:10px; font-weight:700;">Value</td></tr>
               <?php
               
               for ($i = 1; $i < $arrayLength; $i++ )
               {
                $sku = $values[0];
                $head = $header[$i];
                $val = $values[$i];

                if ($head == "description")
                {
                  $val = str_replace("'","\'",$val);
                }

                $key = $head."='".$val."'";

               //prepping for log
                $logheader = $head; //pull header
                $newrecord = $val; //get new value
                $logsku = $sku;
                include 'log.php'; 
                
                $sql = " INSERT into pim (sku, $head) VALUES ('$sku', '$val') ON DUPLICATE KEY UPDATE $key "; 
                $result = mysqli_query($con, $sql) or die(mysqli_error($con)) ; 

                echo "<tr><td style='border:1px solid #000; padding:10px;'>".$sku."</td><td style='border:1px solid #000; padding:10px;'>".$head."</td><td style='border:1px solid #000; padding:10px;'>".$val."</td></tr>";
               }
               echo "</table><br><br>";
               $count++;
             }

           }

           fclose($file);

           echo $count." products update";
     }
  }

  echo "</div></center>";
 ?>
