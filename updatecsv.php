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
                $header = $head; //pull header
                $newvalue = $val; //get new value
                include 'log.php'; 

                $sql = " INSERT into pim (sku, $head) VALUES ('$sku', '$val') ON DUPLICATE KEY UPDATE $key "; 
                $result = mysqli_query($con, $sql) or die(mysqli_error($con)) ;

                echo "Updated or Added ".$sku.", ".$head." = ".$val."<br>";
               }
               echo "<hr>";
               $count++;
             }

           }

           fclose($file);

           echo $count." products update";
     }
  }

  echo "</div>";
 ?>
