<?php
    include '../functions.php';
    loginChecking(array('admin'));
    $username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>

    <title>Account List</title>
</head>
<body>

<?php include '../topbar.php'; ?>
<div class="pim-padding" >
    <nav class="account-nav"><button>Users</button><button>Roles</button></nav>
    <div class="">
<?php
require('../connect.php');
// Prepare and execute the SQL query to select all records
$sql = "SELECT * FROM users";
$result = $con->query($sql);

// Check if there are records in the result set
if ($result->num_rows > 0) {
    // Output data of each row
    echo '<table class="account-list pimtable"><tr><th>User Name</th><th>User Type</th><th>Created Date</th><th></th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row["username"]. '</td><td>' . $row["type"]. '</td><td>' . $row["created"]. '</td><td><form method="POST" action="delete_appro.php"><input type="hidden" name="username" value="'.$username.'"><input type="hidden" name="id" value="'.$row["id"].'"><input type="hidden" name="appro" value="'.$row["appro"].'"><button class="btn" type="submit" name="delete">Delete</button></form></td></tr>';
    }
    echo '</table>';
} else {
    echo '0 results';
}

?>
<a class="btn" href="./add_user.php">Add a new user</a>
    </div>
</div>
</body>
</html>
