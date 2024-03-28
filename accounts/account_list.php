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
        <a class="tab" nav="3">Roles</a>
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
                $roleresult = mysqli_query($con, $sqlrole);

                $pimQuery = getQuery('pim',1);
                $pimresult = getResult($pimQuery , 1);
                $pimrow = mysqli_fetch_assoc($pimresult);

                // Check if the query was successful
                if ($roleresult) {

                    $roles = [];
                    while ($row = mysqli_fetch_assoc($roleresult)) {
                        $roles[] = $row;
                    }
                
                    // Display tab panes using the fetched data
                    $rolenum = 1;
                    echo '<div class="role-buttons ">';
                    foreach ($roles as $role) {
                        echo '<a class="tab" nav="' . $rolenum . '">' . $role['role'] . '<i class="fa fa-caret-right" aria-hidden="true"></i></a>';
                        $rolenum++;
                    }
                    echo '</div>';
                    echo '<div class="tab-content"> ';
                    $contentnum = 1;
                    foreach ($roles as $role) {
                        echo '<div class="tab-pane" tab="' . $contentnum . '">';
                        echo '<form action="update_permission.php" method="post" class="permission-form">';
                        
                        echo '<label class="hidden" for="role">role</label>';
                        echo '<input type="text" class="hidden" name="role" value="'.$role['role'] .'">';
                        echo '<div class="fields-sec">';
                        echo '<h3>Products</h3>';
                        echo '<div class="row">';
                        showcheckbox('addproduct',$role);
                        showcheckbox('deleteproduct',$role);
                        echo '</div>';
                        echo '</div>';

                        echo '<div class="fields-sec">';
                        echo '<h3>Editable Columns</h3>';
                        echo '<div class="row">';
                        showcolumns($pimrow);
                        echo '</div>';
                        echo '</div>';

                        echo '<div class="fields-sec">';
                        echo '<input type="submit" class="btn" value="Update">';
                        echo '</div>';
                        echo '</form></div>';
                        // echo  $role['role'] . $role['addproduct'] . $role['deleteproduct'] . '</div>';
                        $contentnum++;
                    }
                    echo '</div>';

                    // Free the result set
                    mysqli_free_result($roleresult);
                } else {
                    // Handle query errors
                    echo 'Error executing the query: ' . mysqli_error($con);
                }

                // Close the database connection
                mysqli_close($con);

                function showcheckbox($field,$role){
                    echo '<div class="col-md-4">';

                    echo '<input type="checkbox" name = "'.$field.'" class="" ';
                    if  ($role[$field]){ echo 'checked';}
                    echo '>';
                    echo '<label for="'.$field.'">'.$field.'</label>';
                    echo '</div>';
                }
                function showcolumns($pimrow){
                    foreach ($pimrow as $colName => $val) {
                        $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
                        $checked = '';
                        // $usercol =  getValue('users', 'username', $_SESSION['username'], 'type');
                        $selectedcol = explode(',', str_replace('"', '', $usercol));
                        if (in_array($colName, $selectedcol)) {
                            $checked = 'checked';
                        }
                
                        echo '<div class="col-md-2"><input type="checkbox" value="'.$colName.'" name="editproduct[]" class="" '.$checked.'>'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</div>'; 
                    } // show column headers
                }
            ?>
        </div>
    </div>
    


</div>
</body>
</html>
