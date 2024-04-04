
<?php
include 'login_checking.php';
include 'functions.php';
require('connect.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$username = $_SESSION["username"];
$usertype = getValue('users', 'username', $username, 'type');

$usercol = implode(",",getColumns(1));


$filtersString = getColumns(1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'selected_columns' array is set
    $selectedColumnsArray = [];
    if (isset($_POST['check']) && is_array($_POST['check'])) {
        // Loop through the selected checkboxes
        foreach ($_POST['check'] as $selectedColumn) {
            $selectedColumnsArray[] = $selectedColumn;
        }
    }
    $uncommonvalue=[];
    $userColArray = explode(",",$usercol);
    foreach($userColArray as $key=>$uval)
    {
        if(!(in_array($uval,$selectedColumnsArray))){
           $uncommonvalue[]=$uval;
        }

    }
    $array1 = $userColArray;
    $array2 = $selectedColumnsArray;
    $array1 = array_map('trim', str_replace('"', '', $array1));
    $array2 = array_map('trim', str_replace('"', '', $array2));

    $updating_columns = array_diff($array1, $array2);
    $updating_array = array_map(function($item) {
        return "'$item'";
    }, $updating_columns);
    $con->query("update user_columns set status=0 where column_name in (".implode(',',$updating_array).") and user_name='".$_SESSION['username']."' and filter_from='user'");

    $inserting_columns=array_diff($array2, $array1);
    $inserting_array = array_map(function($item) {
        return "'$item'";
    }, $inserting_columns);

    foreach ($inserting_array as $ikey=>$i_array_val)
    {

        $status=1;
        $order_no = maxOrderNo('user_columns');
        $value = $i_array_val;

        $columns = getColumns(0);
        if (!in_array($i_array_val, $columns)) {
            // Insert a new record
            $sql = "INSERT INTO user_columns (`user_name`, `column_name`, `order_no`, `status`, `filter_from`)
                VALUES ('$username', $value, $order_no, $status, 'user')";
        } else {
            // Update the existing record
            $sql = "UPDATE user_columns 
                SET `status` = 1 
                WHERE `column_name` = $value AND `user_name` = '" . $_SESSION['username'] . "' AND `filter_from` = 'user'";
        }
        $con->query($sql);
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
