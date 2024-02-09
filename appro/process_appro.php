<?php

require('../connect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Process other form fields (customer info)
$customerName = $_POST['customer_name'];
$approID = $_POST['appro_id'];
$orderNumber = $_POST['order_number'];
$representation = $_POST['representation'];
$dateEntered = $_POST['date_entered'];
$dueDate = $_POST['due_date'];

// Process items
$items = $_POST['items']; // This will be an array of item arrays

// Serialize the items array
$serializedItems = serialize($items);


// Now you can insert $customerName, $approID, $orderNumber, $dateEntered, $dueDate, and $serializedItems into your database
// Execute your database insert query here, including the serialized items


$approsql = " INSERT into appro (appro,customer,dateentered,datedue,ordernumber,representation,items) VALUES ('$approID','$customerName','$dateEntered','$dueDate','$orderNumber','$representation','$serializedItems')";

$approresult = mysqli_query($con,$approsql) or die(mysqli_error($con)); 
echo "New record created successfully";

}
else{
    echo 'error';
}

?>
