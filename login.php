<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password are set
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        // Your database connection code here
        require('connect.php');

        // Check connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // Sanitize input to prevent SQL injection
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $password = mysqli_real_escape_string($con, $_POST["password"]);


        $query = "SELECT * FROM users";

        // //check what's the error
        // $result = $con->query($query);
        // if (!$result) {
        //     die("Query failed: " . $con->error);
        // }

        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $con->query($query);

        if ($result->num_rows > 0) {
            // User is authenticated
            $_SESSION["username"] = $username;
            header("Location: homepage.php");
            exit();
        } else {
            // Invalid credentials
            $error = "Invalid username or password";
        }

        $con->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>
