<?php

require('../connect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Process other form fields (customer info)
$customerName = $_POST['customer_name'];
$approID = $_POST['appro_id'];
$orderNumber = $_POST['order_number'];
$status = $_POST['status'];
$representation = $_POST['representation'];
$dateEntered = $_POST['date_entered'];
$dueDate = $_POST['due_date'];
$totalQuantity = $_POST['total_quantity'];
$totalPrice = $_POST['total_price'];
$notes = $_POST['notes'];
$username = $_POST['username'];

// Process items
$items = $_POST['items']; // This will be an array of item arrays

// Serialize the items array
$serializedItems = serialize($items);

date_default_timezone_set("Australia/Sydney");
$current = strtotime("now");
$date = date("Y-m-d H:i:s");
$time = date("Y-m-d H:i:s");


for ($i = 0; $i < count($items); $i += 4) {
    $itemcode = $items[$i]['itemcode'];
    $itemprice = $items[$i + 1]['itemprice'];
    $itemquantity = $items[$i + 2]['itemquantity'];
    $itemtotal = $items[$i + 3]['itemtotal'];

    $itemsql = "INSERT INTO approitems (itemcode, itemprice, itemquantity, approid)
            VALUES ('$itemcode', '$itemprice', '$itemquantity', '$approID')";

    $itemresult = mysqli_query($con,$itemsql) or die(mysqli_error($con)); 
}

// Now you can insert $customerName, $approID, $orderNumber, $dateEntered, $dueDate, and $serializedItems into your database
// Execute your database insert query here, including the serialized items


$approsql = " INSERT into appro (appro,customer,itemstatus,dateentered,datedue,ordernumber,representation,items,totalquantity,totalprice,notes) VALUES ('$approID','$customerName','$status','$dateEntered','$dueDate','$orderNumber','$representation','$serializedItems','$totalQuantity','$totalPrice','$notes')";
$approresult = mysqli_query($con,$approsql) or die(mysqli_error($con));

$approlog = " INSERT into approlog (appro,date,time,user,action) VALUES ('$approID','$date','$time','$username','add')";
$logresult = mysqli_query($con,$approlog) or die(mysqli_error($con));



echo "New record created successfully";
header("Location: https://pim.samsgroup.info/appro/appro_list.php");
exit();

}
else{
    echo 'error';
}

?>
