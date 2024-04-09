<?php

namespace controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

use models\User;
use models\Products;
use config\DB;


class ProductApiController
{

    private $storeUrl;
    private $accessToken;


    public function __construct()
    {
        $this->storeUrl = "sga-development.myshopify.com";
        $this->accessToken = "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";
    }

    private function makeRequest($method, $endpoint, $data = null)
    {
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

    public function postData($productData)
    {
        return $this->makeRequest('POST', 'products', $productData);
    }

    public function getData($attribute)
    {

        return $this->makeRequest('GET', $attribute);
    }

    public function getProducts()
    {

        $products = $this->getData('products');

        if ($products !== false) {
            echo "Successfully fetched products data from Shopify:\n";
            dd($products);
        } else {
            echo "Failed to fetch products data from Shopify.\n";
        }

    }

    public function createProduct()
    {
        $products = Products::take(10)->get();
        $users= DB::select("select * from users");
        dd($users);

        $newProductData = [
           $products
        ];
        $createdProduct = $this->postData($newProductData);
        dd($createdProduct);
        if ($createdProduct !== false) {
            echo "Successfully created a new product:\n";
            print_r($newProductData);
        } else {
            echo "Failed to create a new product.\n";
        }
    }
}
