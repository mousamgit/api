<?php
    include '../login_checking.php';
    include '../functions.php';

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
    echo '<table><tr><th>Appro ID</th><th>Customer Name</th><th>Order Number</th><th>Date Entered</th><th>Due Date</th><th>total Quantity</th><th>Total Price</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr><td><a href="/appro/appro.php?id='.$row["appro"].'">' . $row["appro"]. '</a></td><td>' . $row["customer"]. '</td><td>' . $row["ordernumber"]. '</td><td>' . $row["dateentered"]. '</td><td>' . $row["datedue"]. '</td><td>' . $row["totalquantity"]. '</td><td>' . $row["totalprice"]. '</td></tr>';
    }
    echo '</table>';
} else {
    echo '0 results';
}
?>
</div>
</body>
</html>

