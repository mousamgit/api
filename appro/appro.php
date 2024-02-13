<?php
include '../login_checking.php';
    include '../functions.php';
    $approid = $_GET['id'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>

    <title>Appro</title>
</head>
<body>
<?php include '../topbar.php'; ?>
<div class="pim-padding">
    <div class="row">
        <div class="col-md-4">Appro ID: <?php echo $approid; ?></div>
        <div class="col-md-4">Customer: <?php echo getValue('appro', 'appro', $approid, 'customer') ?></div>
        <div class="col-md-4">Order Number: <?php echo getValue('appro', 'appro', $approid, 'ordernumber') ?></div>
        <div class="col-md-4">Date Entered: <?php echo getValue('appro', 'appro', $approid, 'dateentered') ?></div>
        <div class="col-md-4">Date Due: <?php echo getValue('appro', 'appro', $approid, 'datedue') ?></div>
        <div class="col-md-4">Representation: <?php echo getValue('appro', 'appro', $approid, 'representation') ?></div>
    </div>
    <h2>Items:</h2>
    <div class="row">
    <div class="col-md-4">Itme Code</div>
    <div class="col-md-4">Itme Price</div>
    <div class="col-md-4">Itme Quantity</div>
    

    <?php 
    $serializedItems = getValue('appro', 'appro', $approid, 'items');

$items = unserialize($serializedItems);

// Check if the deserialization was successful
if ($items !== false && is_array($items)) {
    // Outer loop to iterate over each item
    foreach ($items as $item) {
        // Inner loop to iterate over each field in the item

        foreach ($item as $field => $value) {
            // Output each field and its value
            echo '<div class="col-md-4">'.$value.'</div>';
        }
        
    }
    echo '</div>';
} else {
    echo "Error: Unable to unserialize items data.";
}

    ?>
</div>
</body>
</html>