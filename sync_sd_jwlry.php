<?php
include ('connect.php');

// Select records
$query = 'SELECT * FROM pim WHERE (image1 <> "" && description <> "" && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "sapphire dreams" && collections_2 <> "steve" && collections_2 <> "discontinued" && collections_2 <> "wholesale_only" && collections <> "sds" && collections <> "melee")';
$result = mysqli_query($con, $query) or die(mysqli_error($con));

// Display HTML content
echo "<h1>SD Jewellery Sync Updated</h1>";
echo "<center><table border=1 cellspacing=0 cellpadding=10>";
echo "<thead>";
echo "<tr>";
echo "<th>sku</th>";
echo "<th>sync_shopify</th>";
echo "</tr>";
echo "</thead>";

// Process data and update database
while ($row = mysqli_fetch_assoc($result)) {
    // Update sync_shopify in the database
    $sku = $row['sku'];
    $sql = "UPDATE pim SET sync_shopify = 1 WHERE sku = '$sku'";
    $result_update = mysqli_query($con, $sql);

    // Handle errors if needed
    if (!$result_update) {
        // Handle the error, e.g., log it or send an error response
        die(mysqli_error($con));
    }

    // Display row in HTML table
    echo "<tr>";
    echo "<td>{$row['sku']}</td>";
    echo "<td>{$row['sync_shopify']}</td>";
    echo "</tr>";
}

// Close HTML table
echo "</table></center><br>";

// Close the database connection
mysqli_close($con);
?>
