<?php
function getQuery($db){
    require('connect.php');

    // Initial query without pagination or filtering
    $baseQuery = "SELECT * FROM $db";
    // Extract all parameters and their values from the URL
    $urlData = $_GET;
    // Initialize an array to store conditions
    $conditions = [];
    foreach ($urlData as $key => $value) {
        // Ensure that the key is alphanumeric to prevent SQL injection
        if ($key != 'page') {
            // Check if the key contains "~" for "contains" filter
            if (strpos($value, '~') === 0) {
                $searchTerm = substr($value, 1);
                $conditions[] = "$key LIKE '%" . mysqli_real_escape_string($con, $searchTerm) . "%'";
            } elseif (strpos($key, '>=') === 0) {
                $conditions[] = substr($key, 2) . " >= " . mysqli_real_escape_string($con, $value);
            } elseif (strpos($key, '<=') === 0) {
                $conditions[] = substr($key, 2) . " <= " . mysqli_real_escape_string($con, $value);
            } else {
                $conditions[] = "$key = '" . mysqli_real_escape_string($con, $value) . "'";
            }
        }
    }
    
    // Check if there are conditions to add
    if (!empty($conditions)) {
        $baseQuery .= " WHERE " . implode(' AND ', $conditions);
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
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $con->error;
    }

    // Close conection
    $con->close();
}
?>