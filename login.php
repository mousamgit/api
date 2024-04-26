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
            header("Location: /");
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
    <?php include 'header.php'; ?>
</head>
<body>
    
    <div class="login-container">
        <div class="login-box">
            <img src="sga-logo.jpg" style="padding-bottom:20px; width:200px;">
            <form method="post" action="">
                <div style="display:table; margin:0 auto;">
                <p style="display:table-row;">
                    <label for="username" style="display:table-cell; width:10%; text-align:left;">Username:</label>
                    <input type="text" id="username" name="username" style="margin-bottom:20px; display:table-cell; width:90%;" required >
                </p>
                <p style="display:table-row;">
                    <label for="password" style="display:table-cell; width:10%; text-align:left;">Password:</label>
                    <input type="password" id="password" name="password" style="margin-bottom:20px; display:table-cell; width:90%;" required>
                </p>
                </div>
                <button type="submit" style="padding:5px 20px; border-radius:5px;">Login</button>
            </form>
            <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        </div>
    </div>
</body>
</html>
