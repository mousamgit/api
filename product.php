<?php
  $sku = $_GET['sku'];

  require_once ('connect.php');

  $query = " SELECT * from pim WHERE SKU = '".$sku."'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  $row = mysqli_fetch_assoc($result);


?>

<html>
  <head>
    <title> SGA PIM - SKU: <?php echo $sku; ?> </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  </head>
  <script type="text/javascript">$(document).ready( function () { $('#myTable').DataTable();} );</script>
  Product page for <?php echo $sku; ?> <br><br>

  <?php

    $imgURL = 'https://samsgroup.info/pim-images/'.$row['SKU'];
    $imgFile = $_SERVER['DOCUMENT_ROOT'].'/pim-images/'.$row['SKU'];
  ?>
    <table border=1 cellpadding=10 cellspacing=0>
      <tr>

        <?php
          if ( file_exists($imgFile.".jpg") )
          {
            echo "<td width=200px><img src='".$imgURL.".jpg' width=100%></td>";
          }
          for ( $i = 0; $i <= 8; $i++ )
          {
            if ( file_exists($imgFile."_".$i.".jpg") )
            {
              echo "<td width=200px><img src='".$imgURL."_".$i.".jpg' width=100%></td>";
            }
          }
        ?>
      </tr>
    </table>
    <br>
  <?php

    foreach ($row as $colName => $val) {
      echo $colName.": ".$row[$colName]."<br>";
    }
  ?>


  <body>
  </body>
</html>
