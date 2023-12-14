<?php

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