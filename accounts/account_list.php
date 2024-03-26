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
    <nav class="account-nav tabs">
        <a class="tab" nav="1">Info</a>
        <a class="tab active" nav="2">Accounts</a>
        <a class="tab" nav="3">Role</a>
    </nav>
    <div class="tab-content">
        <div class="tab-pane" tab="1">
            <h2>Info</h2>
        </div>
        <div class="tab-pane active" tab="2">
         
        <?php
            // Prepare and execute the SQL query to select all records
            require('../connect.php');
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
        <div class="tab-pane" tab="3">
         
            <?php
                require('../connect.php');
                $sqlrole = "SELECT * FROM permissions";
                $result1 = mysqli_query($con, $sqlrole);

                // Check if the query was successful
                if ($result1) {

                    $roles = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $roles[] = $row;
                    }
                
                    // Display tab panes using the fetched data
                    $rolenum = 1;
                    echo '<div class="row"><div class="role-buttons col-md-3">';
                    foreach ($roles as $role) {
                        echo '<a class="tab" nav="' . $rolenum . '">' . $role['role'] . '<i class="fa fa-caret-right" aria-hidden="true"></i></a>';
                        $rolenum++;
                    }
                    echo '</div>';
                    echo '<div class="tab-content col-md-9">';
                    $contentnum = 1;
                    foreach ($roles as $role) {
                        echo '<div class="tab-pane" tab="' . $contentnum . '">' . $role['role'] . $role['addproduct'] . $role['deleteproduct'] . '</div>';
                        $contentnum++;
                    }
                    echo '</div></div>';

                    // Free the result set
                    mysqli_free_result($result1);
                } else {
                    // Handle query errors
                    echo 'Error executing the query: ' . mysqli_error($con);
                }

                // Close the database connection
                mysqli_close($con);
            ?>
        </div>
    </div>
    


</div>
</body>
</html>
