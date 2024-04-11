<?php

namespace controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

use http\Env\Request;
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
    public function mypimdata()
    {
        return View('shopify.mypimdata');
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
        $data = json_decode(file_get_contents("php://input"), true);

        $exporting_rows =$data['exportRows'];
        [id] => 7767856316591
        [title] => Blush Adelaide Ring
    [body_html] =>

A stunning blend of natural Australian Argyle pink diamonds with fine white diamonds. Crafted in 18ct rose and white gold.

    [vendor] => Pink Kimberley Diamonds
    [product_type] => Rings
    [created_at] => 2024-03-20T21:37:35-04:00
                    [handle] => blush-adelaide-ring-bpr-cscpb0101
    [updated_at] => 2024-03-20T21:37:38-04:00
                    [published_at] =>
                    [template_suffix] =>
                    [published_scope] => global
                    [tags] => 18ct Rose & White Gold, Blush Pink Diamonds, FLP, MATTHEWS_STOCK, Rings, Rose Gold Metal, Round, SIAV
    [status] => active
    [admin_graphql_api_id] => gid://shopify/Product/7767856316591
                    [variants] => Array
    (
        [0] => Array
        (
            [id] => 43368347304111
                                    [product_id] => 7767856316591
                                    [title] => Default Title
    [price] => 8495.00
                                    [sku] => BPR-CSCPB0101
    [position] => 1
                                    [inventory_policy] => deny
    [compare_at_price] =>
                                    [fulfillment_service] => manual
    [inventory_management] => shopify
    [option1] => Default Title
    [option2] =>
                                    [option3] =>
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [taxable] => 1
                                    [barcode] =>
                                    [grams] => 0
                                    [weight] => 0
                                    [weight_unit] => kg
    [inventory_item_id] => 45398147104943
                                    [inventory_quantity] => 1
                                    [old_inventory_quantity] => 1
                                    [requires_shipping] => 1
                                    [admin_graphql_api_id] => gid://shopify/ProductVariant/43368347304111
                                    [image_id] => 37646819393711
                                )

                        )

                    [options] => Array
    (
        [0] => Array
        (
            [id] => 9869813711023
                                    [product_id] => 7767856316591
                                    [name] => Title
    [position] => 1
                                    [values] => Array
    (
        [0] => Default Title
                                        )

                                )

                        )

                    [images] => Array
    (
        [0] => Array
        (
            [id] => 37646819393711
                                    [alt] => Blush Adelaide Ring
    [position] => 1
                                    [product_id] => 7767856316591
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [admin_graphql_api_id] => gid://shopify/ProductImage/37646819393711
                                    [width] => 800
                                    [height] => 800
                                    [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/BPR-CSCPB0101.jpg?v=1710985055
                                    [variant_ids] => Array
    (
        [0] => 43368347304111
    )

                                )

                            [1] => Array
    (
        [id] => 37646819426479
                                    [alt] =>
                                    [position] => 2
                                    [product_id] => 7767856316591
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [admin_graphql_api_id] => gid://shopify/ProductImage/37646819426479
                                    [width] => 800
                                    [height] => 800
                                    [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/BPR-CSCPB0101_2.jpg?v=1710985055
                                    [variant_ids] => Array
    (
    )

                                )

                            [2] => Array
    (
        [id] => 37646819459247
                                    [alt] =>
                                    [position] => 3
                                    [product_id] => 7767856316591
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [admin_graphql_api_id] => gid://shopify/ProductImage/37646819459247
                                    [width] => 800
                                    [height] => 800
                                    [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/BPR-CSCPB0101_4.jpg?v=1710985055
                                    [variant_ids] => Array
    (
    )

                                )

                            [3] => Array
    (
        [id] => 37646819492015
                                    [alt] =>
                                    [position] => 4
                                    [product_id] => 7767856316591
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [admin_graphql_api_id] => gid://shopify/ProductImage/37646819492015
                                    [width] => 800
                                    [height] => 800
                                    [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/BPR-CSCPB0101_5.jpg?v=1710985055
                                    [variant_ids] => Array
    (
    )

                                )

                            [4] => Array
    (
        [id] => 37646819524783
                                    [alt] =>
                                    [position] => 5
                                    [product_id] => 7767856316591
                                    [created_at] => 2024-03-20T21:37:35-04:00
                                    [updated_at] => 2024-03-20T21:37:35-04:00
                                    [admin_graphql_api_id] => gid://shopify/ProductImage/37646819524783
                                    [width] => 1000
                                    [height] => 1000
                                    [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/bp-box_8daf5c3b-b1f5-4ca0-925b-23573626b99f.jpg?v=1710985055
                                    [variant_ids] => Array
    (
    )

                                )

                        )

                    [image] => Array
    (
        [id] => 37646819393711
                            [alt] => Blush Adelaide Ring
    [position] => 1
                            [product_id] => 7767856316591
                            [created_at] => 2024-03-20T21:37:35-04:00
                            [updated_at] => 2024-03-20T21:37:35-04:00
                            [admin_graphql_api_id] => gid://shopify/ProductImage/37646819393711
                            [width] => 800
                            [height] => 800
                            [src] => https://cdn.shopify.com/s/files/1/0643/8623/6591/files/BPR-CSCPB0101.jpg?v=1710985055
                            [variant_ids] => Array
    (
        [0] => 43368347304111
    )

                        )

                )

        foreach($exporting_rows as $row)
        {

//            $createdProduct = $this->postData($exporting_row);
        }


        if ($createdProduct !== false) {
            echo "Successfully created a new product:\n";
            print_r($newProductData);
        } else {
            echo "Failed to create a new product.\n";
        }
    }
}
