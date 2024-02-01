<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

//  database connection
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$productId = $data['productId'];
$productName= $data['productName'];
$productType = 'Smart';
$currentDateTime = date("Y-m-d H:i:s");


// Insert/update channel data into the database
if ($productId == 0) {
    $sql = "INSERT INTO products (`name`, `type`, `status`, `created_at`, `updated_at`) 
        VALUES ('$productName', '$productType', 1, '$currentDateTime', '$currentDateTime')";
    if ($con->query($sql) === TRUE) {
        $success = true;
    }
} else {

    $sql_update = "UPDATE products SET name = '$productName',updated_at='$currentDateTime' WHERE id = '$productId'";

    if ($con->query($sql_update) === TRUE) {

        $success = true;
    }
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
