<?php
    include '../login_checking.php';
    include '../functions.php';
    $username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>

    <title>Appro List</title>
</head>
<body>

<?php include '../topbar.php'; ?>
<div class="pim-padding">
    
<?php
require('../connect.php');
// Prepare and execute the SQL query to select all records
$sql = "SELECT * FROM appro";
$result = $con->query($sql);

// Check if there are records in the result set
if ($result->num_rows > 0) {
    // Output data of each row
    echo '<table class="appro-list pimtable"><tr><th>Appro ID</th><th>Customer Name</th><th>Order Number</th><th>status</th><th>Date Entered</th><th>Due Date</th><th></th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr><td><a href="/appro/appro.php?id='.$row["id"].'">' . $row["appro"]. '</a></td><td>' . $row["customer"]. '</td><td>' . $row["ordernumber"]. '</td><td>' . $row["itemstatus"]. '</td><td>' . $row["dateentered"]. '</td><td>' . $row["datedue"]. '</td><td><form method="POST" action="delete_appro.php"><input type="hidden" name="username" value="'.$username.'"><input type="hidden" name="id" value="'.$row["id"].'"><input type="hidden" name="appro" value="'.$row["appro"].'"><button class="btn" type="submit" name="delete">Delete</button></form></td></tr>';
    }
    echo '</table>';
} else {
    echo '0 results';
}
?>
<a class="btn" href="./add_appro.php">Add a new appro</a>
</div>
</body>
</html>

