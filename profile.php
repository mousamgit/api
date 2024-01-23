
<?php
include 'login_checking.php';
include 'functions.php';
require('connect.php');

$username = $_SESSION["username"];
$usertype = getValue('users', 'username', $username, 'type');
$usercol = getValue('users', 'username', $username, 'columns');


$filtersString = getValue('users', 'username', $username, 'filters');

// Check if the form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
//     $value = $_POST["column"];
//     updateValue('users','username',$username,'columns',$value);

//     // Display a success message or redirect the user
//     echo "<h2>Update Completed</h2>";
// }



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'selected_columns' array is set
    $selectedColumnsArray = [];
    if (isset($_POST['check']) && is_array($_POST['check'])) {
        // Loop through the selected checkboxes
        foreach ($_POST['check'] as $selectedColumn) {
            $selectedColumnsArray[] = $selectedColumn;
        }
    }

    $value = '"' . implode('","', $selectedColumnsArray) . '"';
    updateValue('users','username',$username,'columns',$value);
}


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
   
<div id="app" class="app-container">
    <p>Your user type is <?php echo $usertype; ?></p><br>
    <?php
    // Convert the string into an array using explode
    $filters = explode(',', $filtersString);
    $filterCount = count($filters);

    // Iterate through the filters, excluding the last one
    for ($index = 0; $index < $filterCount - 1; $index++) {
        $filter = trim($filters[$index]);
        echo '<a class="btn" href="' . $filter . '">' . $filter. '</a> <a class="btn" href="savefilter.php?remove='.$filter.'">Remove Filter</a><br>';
        // echo $filter;
    }
    ?>
    <br><p>Your default columns:</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="row">
    <?php
    $baseQuery = getQuery('pim',1);
    $result = getResult($baseQuery , 1);
    $row = mysqli_fetch_assoc($result);
    foreach ($row as $colName => $val) { 
        $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
        $checked = '';
        $selectedcol = explode(',', str_replace('"', '', $usercol));
        if (in_array($colName, $selectedcol)) {
            $checked = 'checked';
        }
        // echo '<a class="btn colfilter" >'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</a>' 
        echo '<div class="col-md-2"><input type="checkbox" value="'.$colName.'" name="check[]" class="" '.$checked.'>'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</div>'; 
    } // show column headers
    ?>
    </div>

        <input type="submit" value="Update">
    </form>


</div>
    <script>
    var usercol = [<?php echo $usercol; ?>];
    const callmyapp = myapp.mount('#app');
    </script>

</body>
</html>
