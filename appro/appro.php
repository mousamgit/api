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
        <div class="col-md-4">Status: <?php echo getValue('appro', 'appro', $approid, 'itemstatus') ?></div>
        <div class="col-md-4">Date Entered: <?php echo getValue('appro', 'appro', $approid, 'dateentered') ?></div>
        <div class="col-md-4">Date Due: <?php echo getValue('appro', 'appro', $approid, 'datedue') ?></div>
        <div class="col-md-4">Representation: <?php echo getValue('appro', 'appro', $approid, 'representation') ?></div>
        <div class="col-md-12">Notes: <?php echo getValue('appro', 'appro', $approid, 'notes') ?></div>
    </div>
    <h2>Items:</h2>

    
    <div class="table itemtable">
            <div class="row firstrow">
                <div class="cell">Sku</div>

                <div class="cell">Price</div>
                <div class="cell">Qty</div>
                <div class="cell">Total</div>
            </div>
            
            <?php 
    $serializedItems = getValue('appro', 'appro', $approid, 'items');

$items = unserialize($serializedItems);

// Check if the deserialization was successful
if ($items !== false && is_array($items)) {
    $itemindex = 0;
    echo '<div class="row">';
    foreach ($items as $item) {

        foreach ($item as $field => $value) {
            if ($field == 'itemcode' && $itemindex != 0) {
                echo '</div><div class="row"><div class="cell">'.$value.'</div>';
            }
            else{
                echo '<div class="cell">'.$value.'</div>';
            }
            $itemindex ++;
        }
        
    }
    echo '</div>';
} else {
    echo "Error: Unable to unserialize items data.";
}

    ?>
            
            <div class="row">
                <div class="cell">Total</div>

                <div class="cell"></div>
                <div class="cell"><?php echo getValue('appro', 'appro', $approid, 'totalquantity') ?></div>
                <div class="cell"><?php echo getValue('appro', 'appro', $approid, 'totalprice') ?></div>
            </div>

        </div>

</div>
</body>
</html>