<?php
  include '../login_checking.php';
  include '../functions.php';
  require ('../connect.php');

  $query = " SELECT * from repairs ORDER BY `repairs`.`id` DESC";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
?>
<html>
  <head>
    <?php include '../header.php'; ?>
    <title> SGA PIM - Repairs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  </head>
  <?php include '../topbar.php'; ?>
  <div class="pim-padding">
  <div style="float:left; width:50%;"> <h3>Repair Centre</h3> </div>
  <div style="float:right; width:49%; text-align:right"><a class="task_button" href="./add_repairjob.php"><i class="fa-solid fa-plus"></i> Log a Repair</a></div>
  <br><br><br><br>
  <div style="width:100%;">
  <table id="myTable" class="producttable">
        <thead>
            <tr style="font-size: 14px; color:#fff;">
                <th>Log Number</th>
                <th>Date Created</th>
                <th>Job Number</th>
                <th>Customer Code</th>
                <th>Due Date</th>
                <th>Department</th>
                <th>User</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while ($row = mysqli_fetch_assoc($result)){
                        if ($row[repair_type] == "watch") { $depcolor = '#b58946';} else { $depcolor = '#c792d1';}
                        if ($row[status] == "pending approval") { $statcolor = '#ed9c77';} 
                        elseif ($row[status] == "shipped") { $statcolor = '#c4c2c2';} else { $statcolor = '#fff';}
                        echo "<tr class='clickable-row' data-href='./view_repair.php?id=".$row[id]."'>";
                        echo "<td>".$row[id]."</td>";
                        echo "<td>".$row[added_date]."</td>";
                        echo "<td><a href='./view_repair.php?id=".$row[id]."'>".$row[job_number]."</a></td>";
                        echo "<td>".$row[cust_code]."</td>";
                        echo "<td>".$row[due_date]."</td>";
                        echo "<td style='color:$depcolor; font-weight:bold;'>".strtoupper($row[repair_type])."</td>";
                        echo "<td>".ucwords($row[team_member])."</td>";
                        echo "<td bgcolor=$statcolor>".ucwords($row[status])."</td>";
                        echo "</tr>";
                    }
                ?>
        </tbody>
    </table>
    </div>
                </div>
    <script>
    $('#myTable').DataTable( {
    order: [[0, 'desc']]
} );

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
    </script>
</html>