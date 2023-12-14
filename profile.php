<?php
session_start();
include 'functions.php';
// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
else{
    $username = $_SESSION["username"];
    
    $usertype = getValue('users', 'username', $username, 'type');
    $usercol = getValue('users', 'username', $username, 'columns');
}
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
    <title>Homepage</title>
</head>
<body>
    <a href="/pim/homepage.php">homepage</a>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <p>Your user type is <?php echo $usertype; ?></p>
    <p>Your default columns:</p>
    <p><?php echo $usercol; ?></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <label for="column">New Value:</label>
        <textarea name="column" rows="4" cols="50" required></textarea>
        <br>

        <input type="submit" value="Update">
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>
