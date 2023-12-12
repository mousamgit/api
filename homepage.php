<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
else{
    $username = $_SESSION["username"];
}

function getAttribute($sku, $attribute) {
    require('connect.php');
    // Construct the SQL query
    $escapedAttribute = mysqli_real_escape_string($con, $attribute);
    $sql = "SELECT `$escapedAttribute` FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        // return $row[$attribute];
        return $row[$escapedAttribute];
    } else {
        // Handle query error (you might want to log or display an error message)
        return 'not found';
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <p>This is your homepage content.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
