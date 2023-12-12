<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
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
    <h2>Welcome, <?php echo $_SESSION["type"]; ?>!</h2>
    <p>This is your homepage content.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
