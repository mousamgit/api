<?php
  include 'login_checking.php';
  include 'functions.php';
  require ('connect.php');

  $query = " SELECT * from repairs ORDER BY `repairs`.`id` DESC";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
?>
<html>
  <head>
    <?php include 'header.php'; ?>
    <title> SGA PIM - Repairs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  </head>
  <?php include 'topbar.php'; ?>
  <center><h2>Repairs</h2></center>
  <div style="width:100%; padding:20px;">
  <table id="myTable" class="producttable">
        <thead>
            <tr>
                <th style="color:#fff; font-size:14px;">Type</th>
                <th style="color:#fff; font-size:14px;">Job Number</th>
                <th style="color:#fff; font-size:14px;">Customer Code</th>
                <th style="color:#fff; font-size:14px;">Date Added</th>
                <th style="color:#fff; font-size:14px;">Due Date</th>
                <th style="color:#fff; font-size:14px;">User</th>
                <th style="color:#fff; font-size:14px;">Status</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while ($row = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        if ($row[repair_type] == "watch") { echo "<td bgcolor='#b58946'>".$row[repair_type]."</td>"; } else { echo "<td bgcolor='#E4DDFF'>".$row[repair_type]."</td>"; }
                        echo "<td><a href='https://pim.samsgroup.info/view_repair.php?id=".$row[id]."'>".$row[job_number]."</a></td>";
                        echo "<td>".$row[cust_code]."</td>";
                        echo "<td>".$row[added_date]."</td>";
                        echo "<td>".$row[due_date]."</td>";
                        echo "<td>".$row[team_member]."</td>";
                        echo "<td>".$row[status]."</td>";
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