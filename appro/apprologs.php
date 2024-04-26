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

    <title>Appro Logs</title>
</head>
<body>

<?php include '../topbar.php'; ?>
<div class="pim-padding">
    
<?php
require('../connect.php');
// Prepare and execute the SQL query to select all records
$sql = "SELECT * FROM approlog";
$result = $con->query($sql);

// Check if there are records in the result set
if ($result->num_rows > 0) {
    // Output data of each row
    echo '<table class="appro-list pimtable"><tr><th>Appro</th><th>Field</th><th>Old Record</th><th>New Record</th><th>Date</th><th>Time</th><th>User</th><th>Action</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row["appro"]. '</td><td>' . $row["field"]. '</td><td>' . $row["oldrecord"]. '</td><td>' . $row["newrecord"]. '</td><td>' . $row["date"]. '</td><td>' . $row["time"]. '</td><td>' . $row["user"]. '</td><td>' . $row["action"]. '</td></tr>';
    }
    echo '</table>';
} else {
    echo '0 results';
}
?>

</div>
</body>
</html>

