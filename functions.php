<?php
//require('./connect.php');
function loginChecking($allowed){
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: https://pim.samsgroup.info/login.php");
        exit();
    }
    if (!checkType($allowed)){
        header("Location: https://pim.samsgroup.info/notallowed.php");
        exit();
    }
}

function checkType($allowed){
    
    require('connect.php');
    $userType = getValue('users', 'username', $_SESSION['username'], 'type');
    $access = false;

    if($allowed == 'all'){
        $access = true;
    }
    else{
        
        for ($i = 0; $i < count($allowed); $i++) {
            if($allowed[$i] == $userType){
                $access = true;
            }
        }
    }
    return $access;
}
function checkLevel($allowed){
    require('connect.php');
    $userLevel = getValue('users', 'username', $_SESSION['username'], 'level');
    if($userLevel <= $allowed){
        return true;
    }
    else{
        return false;
    }
}
function valuefromString($string, $symbol, $element){
    $keyParts = explode($symbol, $string);
    return $keyParts[$element];
}
function maxOrderNo($table)
{
    require('connect.php');
    $maxOrderNoQuery = "SELECT MAX(order_no) AS max_order_no FROM ".$table." where user_name ='".$_SESSION['username']."'";
    $maxOrderNoResult =mysqli_query($con, $maxOrderNoQuery);

    if ($maxOrderNoResult) {
        $row = mysqli_fetch_assoc($maxOrderNoResult);
        $maxOrderNo = $row['max_order_no'];
        $newOrderNo = $maxOrderNo + 1;
    } else {
        $newOrderNo = 1;
    }
    return $newOrderNo;
}
function getQuery($db){
    require('connect.php');

    // Initial query without pagination or filtering
    $baseQuery = "SELECT * FROM $db";
    // Extract all parameters and their values from the URL
    $urlData = $_GET;
    // Initialize an array to store conditions
    $conditions = [];
    $filterlogic;
    foreach ($urlData as $key => $value) {
        // Ensure that the key is alphanumeric to prevent SQL injection
        if ($key != 'page' && $key != 'logic') {
            // Check if the key contains "~" for "contains" filter
            if (strpos($key, '~') !== false) {
                // $keyParts = explode('~', $key);
                // $columnName = $keyParts[0];
                // $searchTerm = $keyParts[1];
                
                $conName = valuefromString($key, '~', 0);
                $conValue = valuefromString($key, '~', 1);
                $conditions[] = "$conName LIKE '%" . mysqli_real_escape_string($con, $conValue) . "%'";
            }
            elseif (strpos($key, '>') !== false) {
                $conName = valuefromString($key, '>', 0);
                $conValue = valuefromString($key, '>', 1);
                $originalValue = str_replace('_', '.', $conValue);
                $conditions[] = "$conName >= " . $originalValue;
            } elseif (strpos($key, '<') !== false) {
                $conName = valuefromString($key, '<', 0);
                $conValue = valuefromString($key, '<', 1);
                $originalValue = str_replace('_', '.', $conValue);
                $conditions[] = "$conName <= " . $originalValue;
            } else {
                $conditions[] = "$key = '" . mysqli_real_escape_string($con, $value) . "'";
            }

        }
        if($key == 'logic'){
            $filterlogic=$value;
        }
    }

    // Check if there are conditions to add
    if (!empty($conditions)) {
        $baseQuery .= " WHERE " . implode( " $filterlogic ", $conditions);
    }
    return $baseQuery;
  }

function getResult($baseQuery , $records_per_page){
    require('connect.php');
    // Calculate total rows for pagination
    $totalRowsResult = mysqli_query($con, $baseQuery);
    $total_rows = mysqli_num_rows($totalRowsResult);


    // Assuming $result is your SQL query result
  
    $total_pages = ceil($total_rows / $records_per_page);
  
    // Get the current page or set it to 1 if not set
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  
    $offset = ($current_page - 1) * $records_per_page;
  
    // Construct the SQL query with pagination
    $sql = $baseQuery . " LIMIT $offset, $records_per_page";
    $result = mysqli_query($con, $sql);
    return $result;
}

function getTotalPages($baseQuery , $records_per_page){
    require('connect.php');
      // Calculate total rows for pagination
    $totalRowsResult = mysqli_query($con, $baseQuery);
    $total_rows = mysqli_num_rows($totalRowsResult); 
    $total_pages = ceil($total_rows / $records_per_page);
    return $total_pages;
}

function getFilters()
{
    require('connect.php');
    $filter_ids =[];
    $user_id = getValue('users', 'username', $_SESSION['username'], 'id');

    $query="select id from user_filters where user_id=".$user_id;
//    echo $query; die;
    $filters=$con->query($query);
    if($filters->num_rows>0)
    {
        while($row=$filters->fetch_assoc())
        {
            $filter_ids[]=$row['id'];
        }
    }
    return $filter_ids;
}
function getFilterValueHover($filter_no)
{
    require('connect.php');
    $user_name =$_SESSION['username'];

    $query="select * from user_filter_details where user_name='".$user_name."' and filter_no =".$filter_no;

    $filters=$con->query($query);

    if($filters->num_rows>0)
    {
        while ($prevAttributeValue = $filters->fetch_assoc()) {
            switch ($prevAttributeValue["filter_type"]) {
                case "=":
                case "!=":
                case ">":
                case "<":
                    $condition = $prevAttributeValue['attribute_name'] . ' ' . $prevAttributeValue["filter_type"] . ' "' . $prevAttributeValue['attribute_condition'] . '"';
                    break;
                case "includes":
                    $condition = $prevAttributeValue['attribute_name'] . ' LIKE "%' . $prevAttributeValue['attribute_condition'] . '%"';
                    break;
                case "between":
                    $condition = $prevAttributeValue['attribute_name'] . ' BETWEEN ' . $prevAttributeValue['range_from'] . ' AND ' . $prevAttributeValue['range_to'];
                    break;
                default:
                    $condition = $prevAttributeValue['attribute_name'].' IS NOT EMPTY';
                    break;
            }

            if ($prevAttributeValue['op_value'] == 'OR') {
                $filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
                $groupedConditions = [$condition];
            } else {
                $groupedConditions[] = $condition;
            }

        }
        if (!empty($groupedConditions)) {
            $filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
        }

        $filterCondition = implode(' OR ', $filterConditions);

    }
    return $filterCondition;
}
function getColumns($status)
{
    require('connect.php');
    $columnValuesRow = [];
    $userOrderedColumns = $con->query("select column_name from user_columns where user_name ='".$_SESSION["username"]."' and filter_from= 'user' and status=".$status." order by order_no ASC");

    if ($userOrderedColumns->num_rows > 0) {
        while ($row = $userOrderedColumns->fetch_assoc()) {
            $columnValuesRow[]=$row['column_name'];
        }
    }
    return $columnValuesRow;
}
function getValue($db, $prkey, $keyvalue, $attribute) {
    require('connect.php');
    // Construct the SQL query
    $escapedAttribute = mysqli_real_escape_string($con, $attribute);
    $escapedDb = mysqli_real_escape_string($con, $db);
    $escapedKey = mysqli_real_escape_string($con, $prkey);
 

    $sql = "SELECT `$escapedAttribute` FROM `$escapedDb` WHERE `$escapedKey` = '$keyvalue' LIMIT 1";
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

function updateValue($db, $prkey,$keyvalue, $attribute, $value)
{
    require('connect.php');

    // Sanitize input to prevent SQL injection
    $escapedDb = mysqli_real_escape_string($con, $db);
    $escapedKey = mysqli_real_escape_string($con, $prkey);
    $escapedAttribute = mysqli_real_escape_string($con, $attribute);
    $value = mysqli_real_escape_string($con, $value);

    // Update query
    $query = "UPDATE `$escapedDb` SET `$escapedAttribute` = '$value' WHERE `$escapedKey` = '$keyvalue'";
    // Execute query
    if ($con->query($query) === TRUE) {
        echo "<span class='updated'>Record updated successfully</span>";
    } else {
        echo "Error updating record: " . $con->error;
    }
    // Close conection
    $con->close();
}
function addtoLog($logsku, $logheader, $newrecord,$username)
{
    require('connect.php');
    $oldrecord = getValue('pim', 'sku', $logsku, $logheader);
    date_default_timezone_set("Australia/Sydney");
    $current = strtotime("now");
    $date = date("Y-m-d H:i:s");
    $time = date("Y-m-d H:i:s");


    $logsql = " INSERT into pimlog (date,time,sku,field,oldrecord,newrecord,user) VALUES ('$date','$time','$logsku','$logheader','$oldrecord','$newrecord','$username')";
    $logresult = mysqli_query($con,$logsql) or die(mysqli_error($con)); 
}
function valueexist($db, $prkey, $keyvalue){
    require('connect.php');
    // Construct the SQL query

    $escapedDb = mysqli_real_escape_string($con, $db);
    $escapedKey = mysqli_real_escape_string($con, $prkey);
 

    $sql = "SELECT COUNT(*) as count FROM `$escapedDb` WHERE `$escapedKey` = '$keyvalue'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] > 0) {
            return  true;
        } else {
            return  false;
        }
    } else {
        // Handle query error (you might want to log or display an error message)
        return 'not found';
    }
}
function searchdata($sql){
    require('connect.php');

    $result = $con->query($sql);

    // Store results in an array
    $searchResults = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Append each row to the search results array
            $searchResults[] = $row;
        }
    }
    return $searchResults;
    // Close database connection
    $con->close();


}

function duplicatedcheck($db, $colname, $value){
    require('connect.php');
    $value = $con->real_escape_string($value);

    $sql = "SELECT * FROM $db WHERE $colname = '$value'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        return true; 
    } else {
        return false; 
    }
}

function mergeDups($from, $to){
    require('connect.php');

    // Fetch rows from the database based on the specified range
    $query = "SELECT * FROM pimnew WHERE sku BETWEEN $from AND $to";
    $result = mysqli_query($con, $query);

    $mergedRows = array();

    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($mergedRows[$row['sku']])) {
            foreach ($row as $key => $value) {
                if ($key !== 'sku') {
                    $mergedRows[$row['sku']][$key] = $value;
                }
            }
        } else {
            $mergedRows[$row['sku']] = $row;
        }
    }

    foreach ($mergedRows as $sku => $mergedRow) {
        $updateQuery = "UPDATE pimtemp SET ";
        $setValues = array();
        foreach ($mergedRow as $key => $value) {
            // Build the SET part of the SQL query
            $setValues[] = "$key = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        $updateQuery .= implode(", ", $setValues);
        $updateQuery .= " WHERE sku = '" . mysqli_real_escape_string($con, $sku) . "'";

        // Execute the update query
        mysqli_query($con, $updateQuery);
    }
}

?>