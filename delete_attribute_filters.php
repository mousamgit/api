<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$id = $data['id'];



 $deleteQuery = "DELETE FROM attribute_filter WHERE id=" . $id;
        if ($con->query($deleteQuery) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $con->error]);
        }


// Close the database connection
$con->close();
?>
