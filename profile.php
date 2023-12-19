
<?php
include 'login_checking.php';
include 'functions.php';
$username = $_SESSION["username"];
$usertype = getValue('users', 'username', $username, 'type');
$usercol = getValue('users', 'username', $username, 'columns');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $value = $_POST["column"];
    updateValue('users','username',$username,'columns',$value);

    // Display a success message or redirect the user
    echo "<h2>Update Completed</h2>";
} else {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <script src="./js/pimjs.js" ></script>
    <title>User Profile</title>
</head>
<body>
<?php include 'topbar.php'; ?>
   

    <p>Your user type is <?php echo $usertype; ?></p>
    <p>Your default columns:</p>
    <p><?php echo $usercol; ?></p>
    <?php
    require('connect.php');
    $baseQuery = getQuery('pim',1);
    $result = getResult($baseQuery , 1);
    $row = mysqli_fetch_assoc($result);
    foreach ($row as $colName => $val) { 
        $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
        echo '<a class="btn colfilter" >'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</a>'; 
    } // show column headers
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <label for="column">New Value:</label>
        <textarea name="column" rows="4" cols="50" required></textarea>
        <br>

        <input type="submit" value="Update">
    </form>
    <a href="logout.php">Logout</a>
    <script>
    var usercol = [<?php echo $usercol; ?>];
    const callmyapp = myapp.mount('#app');
    </script>

</body>
</html>
