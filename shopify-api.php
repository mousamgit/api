<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

class ShopifyAPI {
    private $storeUrl;
    private $accessToken;

    public function __construct($storeUrl, $accessToken) {
        $this->storeUrl = $storeUrl;
        $this->accessToken = $accessToken;
    }

    private function makeRequest($method, $endpoint, $data = null) {
        $url = "https://{$this->storeUrl}/admin/api/2024-01/{$endpoint}.json";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Shopify-Access-Token: {$this->accessToken}"
        ));

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        if ($statusCode == 200 || $statusCode == 201) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

    public function postData($productData) {
        return $this->makeRequest('POST', 'products', $productData);
    }

    public function getData($attribute) {
        return $this->makeRequest('GET', $attribute);
    }
}

function main($request) {
    //$request = (object) ['method' => 'POST'];
    $storeUrl = "sga-development.myshopify.com"; // Your Shopify store URL
    $accessToken = "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"; // Your access token generated through Shopify app

    // Create ShopifyAPI object
    $shopifyAPI = new ShopifyAPI($storeUrl, $accessToken);
    // Check if the request method is POST
    if ($request->method == 'POST') {
        // Example of creating a new product
        $newProductData = [
            "product" => [
                "title" => "post test fdddpm Mousam Test ProductTitle",
                "body_html" => "<p>post Mousam Test gorgeous blend of natural Australian Argyle pink diamonds with fine white diamonds. </p>",
                "vendor" => "post TEst Pink Kimberley Diamonds",
                "product_type" => "post code  Mousam Earrings",
            ]
        ];
        $createdProduct = $shopifyAPI->postData($newProductData);
        if ($createdProduct !== false) {
            echo "Successfully created a new product:\n";
            print_r($newProductData);
        } else {
            echo "Failed to create a new product.\n";
        }
    }
    elseif ($request->method == 'GET') {
        // Example of fetching product data from Shopify
        $products = $shopifyAPI->getData('products');

        if ($products !== false) {
            echo "Successfully fetched products data from Shopify:\n";
            print_r($products);
        } else {
            echo "Failed to fetch products data from Shopify.\n";
        }
    }
    else {
        echo "Unsupported request method.\n";
    }
}

// Example usage
$request = (object) ['method' => $_SERVER['REQUEST_METHOD']]; // Simulating a GET request
main($request);
?>
