<?php

namespace controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

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

    private function makeGraphQLRequest($query, $variables = [])
    {

        $url = "https://{$this->storeUrl}/admin/api/2024-04/graphql.json";
        $headers = [
            "Content-Type: application/json",
            "X-Shopify-Access-Token: {$this->accessToken}"
        ];

        // Prepare the request payload
        $data = [
            'query' => $query,
            'variables' => $variables
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode == 200 || $statusCode == 201) {
            return json_decode($response, true);
        } else {
            return [
                'status_code' => $statusCode,
                'response_body' => $response
            ];
        }
    }


    public function postData($productData)
    {

input VariantInput {
        sku: String!
        price: Float!
        inventoryPolicy: String
    fulfillmentService: String
    inventoryManagement: String
    cost: Float
    inventoryQuantity: Int
    # Add other variant fields here
}

        $mutation = '
    mutation CreateProduct($input: ProductInput!) {
        productCreate(input: $input) {
              title: String!
              descriptionHtml: String
              vendor: String
              productType: String
              handle: String
              tags: [String]
              status: String
              variants: [VariantInput]   # Add this line to include the variants field
            product {
                id
                title
                description
                vendor
                productType,
                handle,
                tags,
                status,          
            }
            userErrors {
                field
                message
            }
        }
    }
';
        $variables = ['input' => $productData];


        return $this->makeGraphQLRequest($mutation, $variables);
    }

    public function putData($productId, $productData)
    {
        $mutation = '
    mutation CreateProduct($input: ProductInput!) {
        productCreate(input: $input) {
            product {
                id
                title
            }
            userErrors {
                field
                message
            }
        }
    }
';


        return $this->makeGraphQLRequest($mutation, ['id' => $productId, 'input' => $productData]);
    }

    public function getData($query)
    {
        $storeUrl = $this->storeUrl;
        $accessToken = $this->accessToken;

        $url = "https://{$storeUrl}/admin/api/2024-04/graphql.json";
        $headers = [
            "Content-Type: application/json",
            "X-Shopify-Access-Token: {$accessToken}"
        ];

        $query = '{
            products(first: 3) {
                edges {
                    node {
                        id
                        title
                        descriptionHtml
                        vendor
                        productType,
                        handle,
                        tags,
                        status,
                         variants {
                            edges {
                                node {
                                    id
                                    sku
                                    price
                                    inventoryPolicy
                                    fulfillmentService
                                    inventoryManagement
                                    cost
                                    inventoryQuantity
                                }
                            }
                        }
                            images {
                                edges {
                                    node {
                                        id
                                        src
                                    }
                                }
                            }
                        }
                    }
            }
        }';

        $data = [
            'query' => $query
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode == 200 || $statusCode == 201) {
            $responseData = json_decode($response, true);
            dd($responseData);
            // Process the response data as needed
        } else {
            echo "Error: HTTP status code {$statusCode}\n";
            echo "Response: {$response}\n";
        }
    }

    public function getQuantityUpdates($productId)
    {
        $query = '
            product(id: "'.$productId.'") {
                variants {
                    sku
                    # Add other variant fields you need here
                }
            }
        ';

        return $this->getData($query);
    }

    public function getProducts()
    {
        $query = 'products(first: 10) {
            edges {
                node {
                    id
                    title
                    # Add other product fields you need here
                }
            }
        }';

        return $this->getData($query);
    }

    public function createProduct()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $exporting_rows =$data['exportRows'];

        foreach($exporting_rows as $key=> $row)
        {

            //check images
            $imageURL = [];
            if($row['image1'] != "") { $imageURL[] = $row['image1'];}
            if($row['image2'] != "") { $imageURL[] = $row['image2'];}
            if($row['image3'] != "") { $imageURL[] = $row['image3'];}
            if($row['image4'] != "") { $imageURL[] = $row['image4'];}
            if($row['image5'] != "") { $imageURL[] = $row['image5'];}
            if($row['image6'] != "") { $imageURL[] = $row['image6'];}
            if($row['packaging_image'] != "") { $imageURL .= $row['packaging_image'];}



            //Status - draft if steve, discontinued, wholesale only
            $status = "";
            if ( preg_match("/steve/i", strtolower($row['collections_2']))) { $status = "DRAFT"; }
            elseif ( preg_match("/discontinued/i", strtolower($row['collections_2']))) { $status = "DRAFT"; }
            elseif ( preg_match("/wholesale_only/i", strtolower($row['collections_2']))) { $status = "DRAFT"; }
            else { $status = "ACTIVE"; }

            //Command - delete if 0 stock or marked for deletion (deletion = 1), MERGE if in stock but status is draft, MERGE if everything passes
            $command = "";
            if ($row['shopify_qty'] > 0) {
                if ($status == "active") { $command = "MERGE";  }
                if ($status == "draft") { $command = "MERGE"; }
                if ($row['deletion'] == 1) { $command= "DELETE"; }
            }else { $command = "DELETE";}

            // Stone price vs item price
            $itemprice = "";
            if( strtolower($row['type']) == "loose diamonds" ){ $itemprice = $row['stone_price_retail_aud']; } else{ $itemprice = $row['retail_aud']; }

            // Create handle
            $handle ="";
            if( substr($row['sku'],0,3) == "TDR" || substr($row['sku'],0,3) == "TPR" ){ $handle = "argyle-tender-diamond-".$row['shape']."-".$row['colour']."-".$row['clarity']."-".$row['sku']; $handle = strtolower($handle); } elseif( strtolower($row['type']) == "loose diamonds" ) { $handle = ""; $handle = "argyle-pink-diamond-".$row['shape']."-".$row['colour']."-".$row['clarity']."-".$row['sku']; $handle = strtolower($handle); } else{ $handle = ""; $handle = str_replace(" ","-",strtolower($row['product_title'])) ."-". strtolower($row['sku']); }
            $handle = str_replace(["--"," "],"-",$handle);

            // Purchase Cost Calculation
            $purchase_cost = "";
            if( strtolower($row['type']) == "loose diamonds" ) { $purchase_cost = $row['purchase_cost_aud'] * $row['carat']; $purchase_cost = round($purchase_cost,2); } else{ $purchase_cost = $row['purchase_cost_aud']; }

            // Tags
            $tags = "";
            if ( $row['tags'] != "") { $tags .= $row['tags'].", "; }
            if ( $row['brand'] != "" ) { $tags .= $row['brand'].", "; }
            if ( $row['colour'] != "" ) { $tags .= $row['colour'].", "; }
            if ( $row['colour'] != "" ) {
                if (preg_match("/pp/i", strtolower($row['colour'])) > 0){ $tags .= "PP - Purplish Pink, "; }
                elseif (preg_match("/pr/i", strtolower($row['colour'])) > 0){ $tags .= "PR - Pink Rose, "; }
                elseif (preg_match("/pc/i", strtolower($row['colour'])) > 0){ $tags .= "PC - Pink Champagne, "; }
                elseif (preg_match("/bl/i", strtolower($row['colour'])) > 0){ $tags .= "BL - Blue, "; }
                elseif (preg_match("/pred/i", strtolower($row['colour'])) > 0){ $tags .= "pRed - Pinkish Red, "; }
                else { $tags .= "P - Pink, "; }
            }
            if ( $row['shape'] != "" ) { $tags .= $row['shape'].", "; }
            if ( $row['clarity'] != "" ) { $tags .= $row['clarity'].", "; }
            if ( $row['collections'] != "" ) { $tags .= $row['collections'].", "; }
            if ( $row['type'] != "" ) { $tags .= $row['type'].", "; }
            if ( $row['main_metal'] != "" ) { $tags .= $row['main_metal']." Metal, "; }
            if ( $row['preorder'] == 1 ) { $tags .= "Preorder, "; }
            if ( strtolower($row['type']) == "loose diamonds") {
                if ($row['collections'] == "SKS") {
                    $tags .= "pkcertified";
                }
                if ($row['collections'] == "STN") {
                    $tags .= "argylecertified";
                }
            }

            $productData = [
                        'title' => 'yesyes'.$key.' '.$row['product_title'],
                        'descriptionHtml' => $row['description'],
                        'vendor' => $row['brand'],
                        'productType' => $row['type'],
                        'handle' => $handle,
                        'tags' => $tags,
                        'status' => $status,
                        'variants' => [
                            [
                            'sku'=>$row['sku'],
                            'price'=>$itemprice,
                            'inventoryPolicy'=>'deny',
                            'fulfillmentService'=>'manual',
                            'inventoryManagement'=>'shopify',
                            'cost'=>$purchase_cost,
                            'inventoryQuantity'=>80
                            ],
                        ]
            ];

            //save data to product shopify
            $productResponse = $this->postData($productData);
            dd($productResponse);

//            //Check if product creation was successful
//            if ($productResponse !== false && isset($productResponse['product'])) {
//                //$productId = $productResponse['product']['id'];
//                $productId = 7815135166639;
//                $inventory_quantity = 50; // Example: Total quantity in stock
//                $inventory_commitment = 10; // Example: Quantity committed to pending orders
//                $inventory_available = $inventory_quantity - $inventory_commitment; // Calculate available quantity
//                $inventory_on_hand = $inventory_quantity; // On hand quantity, assuming all inventory is available
//
//
//                $variantsData=[
//                    'product'=>[
//                        'variant'=>[
//                            'product_id'=>$productId,
//                            'sku'=>$row['sku'],
//                            'price'=>$itemprice,
//                            'inventory_policy'=>'deny',
//                            'fulfillment_service'=>'manual',
//                            'inventory_management'=>'shopify',
//                            'cost'=>$purchase_cost,
//                            'inventory_quantity_on_hand'=>50,
//                            'committed'=>2,
//                            'inventory_quantity'=>48
//                        ]
//                    ]
//                ];
//
//                //variant data insertion
//                $variantsResponse = $this->putData($productId, $variantsData);
//                dd($variantsResponse);
//
//            dd($productResponse);
                if ($productResponse !== false) {
                    $success=true;
                } else {
                    $success=false;
                }

        }
        if($success==true)
        {
            echo "Congratulations you exported your pim data successfully to shopify";
        }
    }

}

