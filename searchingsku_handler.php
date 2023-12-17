<?php


$sku = $_GET['sku'];

// Simulate a database lookup using the SKU
// Replace this with your actual database query
$productTitle = getAttribute($sku, 'product_title');
$image = getAttribute($sku, 'image1');


// Output the result as JSON
echo json_encode(['sku' => $sku, 'title' => $productTitle, 'image' => $image]);
// echo '<div class="row pro-container"><div class="col-md-3"><img src="'.$image.'"></div><div class="col-md-9"><h3>'.$sku.'</h3><div class="pro-name">'.$productTitle.'</div></div></div>';

// Simulated database functions
function getAttribute($sku, $attribute) {
    require('connect.php');
    // Construct the SQL query
    $escapedAttribute = mysqli_real_escape_string($con, $attribute);
    $sql = "SELECT `$escapedAttribute` FROM pim WHERE sku = '$sku' LIMIT 1";
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


?>
