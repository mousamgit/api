<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
function authenticateShopify($apiKey, $password, $storeUrl) {
    return base64_encode("$apiKey:$password");
}

function fetchProductDataFromPim() {
    require('connect.php');
    // Fetch my pim data
    $sql = "SELECT * from pim limit 10";
    $result = $con->query($sql);
    $products = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $con->close();

    return $products;
}

function doesProductExistInShopify($sku='04-16G', $auth, $baseUrl) {
    $url = "$baseUrl/products.json?sku=$sku";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Basic $auth"
    ));
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Product exists if status code is 200 (OK) and response contains data
    return $statusCode == 200 && !empty(json_decode($response, true)['products']);
}

function updateProductsInShopify($products, $auth, $baseUrl) {

    foreach ($products as $product) {
        $sku = $product['sku'];
        $brand = $product['brand'];
        $title = $product['product_title'];
        $retail_price = $product['retail_aud'];

        // Check if product already exists in Shopify
        $productExists = doesProductExistInShopify($sku, $auth, $baseUrl);

        if ($productExists) {
           //my update case
            echo "Product with SKU $sku exists. Updating...\n";

        } else {
            //my create case
            echo "Product with SKU $sku does not exist. Creating...\n";

        }
         $payload=fetchProductDataFromPim();
         echo $payload; die;
         $url = "$baseUrl/products.json";
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             "Content-Type: application/json",
             "Authorization: Basic $auth"
         ));
         $response = curl_exec($ch);
         $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         curl_close($ch);
    }
}

function main() {
    $apiKey = "your_api_key";
    $password = "your_password";
    $storeUrl = "sga-development.myshopify.com";

    // Authentication part
    $auth = authenticateShopify($apiKey, $password, $storeUrl);
    $baseUrl = "https://$storeUrl/admin/api/2021-10";

    //my pim data
    $products = fetchProductDataFromPim();

    // Update my pim data to shopify store
    updateProductsInShopify($products, $auth, $baseUrl);
}

// Execute main function
main();

?>
