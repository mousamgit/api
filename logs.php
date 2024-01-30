<?php
  include 'login_checking.php';
  include 'functions.php';
  $sku = $_GET['sku'];
  require ('connect.php');

  $logquery = " SELECT * from pimlog ORDER BY `pimlog`.`date` DESC, `pimlog`.`time` DESC ";
  $logresult = mysqli_query($con, $logquery) or die(mysqli_error($con));
?>
<html>
  <head>
    <?php include 'header.php'; ?>
    <title> SGA PIM - Logs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  </head>
  <?php include 'topbar.php'; ?>
  <center><h2>Logs</h2></center>
  <div style="width:100%; padding:20px;">
  <table id="myTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>SKU</th>
                <th>Field</th>
                <th>Old Record</th>
                <th>New Record</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while ($logrow = mysqli_fetch_assoc($logresult)){
                        echo "<tr>";
                        echo "<td>".$logrow[date]."</td>";
                        echo "<td>".$logrow[time]."</td>";
                        echo "<td>".$logrow[sku]."</td>";
                        echo "<td>".$logrow[field]."</td>";
                        if(strpos(strtolower($logrow[oldrecord]),".jpg") !== false || strpos(strtolower($logrow[oldrecord]),".png") !== false) { echo "<td><img src='".$logrow[oldrecord]."' width=150px></td>"; } else { echo "<td>".$logrow[oldrecord]."</td>"; }
                        if(strpos(strtolower($logrow[newrecord]),".jpg") !== false || strpos(strtolower($logrow[newrecord]),".png") !== false) { echo "<td><img src='".$logrow[newrecord]."' width=150px></td>"; } else { echo "<td>".$logrow[newrecord]."</td>"; }
                        echo "<td>".$logrow[user]."</td>";
                        echo "</tr>";
                    }
                ?>
        </tbody>
    </table>
    </div>
    <script>
    $('#myTable').DataTable( {
    order: [[0, 'desc']]
} );
    </script>
</html>