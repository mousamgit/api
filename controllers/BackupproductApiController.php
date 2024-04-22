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


    public function getData($query)
    {
        $storeUrl = $this->storeUrl;
        $accessToken = $this->accessToken;

        $url = "https://{$storeUrl}/admin/api/2024-04/graphql.json";
        $headers = [
            "Content-Type: application/json",
            "X-Shopify-Access-Token: {$accessToken}"
        ];

        $query = $query;

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
            return $responseData;
            // Process the response data as needed
        } else {
            echo "Error: HTTP status code {$statusCode}\n";
            echo "Response: {$response}\n";
        }
    }


   
    public function createProduct()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $exporting_rows = $data['exportRows'];
        //dd($exporting_rows);

        foreach ($exporting_rows as $key => $row) {
            
            $imageURL = [];
            if ($row['image1'] != "") {
                $imageURL[] = $row['image1'];
            }
            if ($row['image2'] != "") {
                $imageURL[] = $row['image2'];
            }
            if ($row['image3'] != "") {
                $imageURL[] = $row['image3'];
            }
            if ($row['image4'] != "") {
                $imageURL[] = $row['image4'];
            }
            if ($row['image5'] != "") {
                $imageURL[] = $row['image5'];
            }
            if ($row['image6'] != "") {
                $imageURL[] = $row['image6'];
            }
            if ($row['packaging_image'] != "") {
                $imageURL[] = $row['packaging_image'];
            }
           

            //Status - draft if steve, discontinued, wholesale only
            $status = "";
            if (preg_match("/steve/i", strtolower($row['collections_2']))) {
                $status = "DRAFT";
            } elseif (preg_match("/discontinued/i", strtolower($row['collections_2']))) {
                $status = "DRAFT";
            } elseif (preg_match("/wholesale_only/i", strtolower($row['collections_2']))) {
                $status = "DRAFT";
            } else {
                $status = "ACTIVE";
            }

            //Command - delete if 0 stock or marked for deletion (deletion = 1), MERGE if in stock but status is draft, MERGE if everything passes
            $command = "";
            if ($row['shopify_qty'] > 0) {
                if ($status == "active") {
                    $command = "MERGE";
                }
                if ($status == "draft") {
                    $command = "MERGE";
                }
                if ($row['deletion'] == 1) {
                    $command = "DELETE";
                }
            } else {
                $command = "DELETE";
            }

            // Stone price vs item price
            $itemprice = "";
            if (strtolower($row['type']) == "loose diamonds") {
                $itemprice = $row['stone_price_retail_aud'];
            } else {
                $itemprice = $row['retail_aud'];
            }

            // Create handle
            $handle = "";
            if (substr($row['sku'], 0, 3) == "TDR" || substr($row['sku'], 0, 3) == "TPR") {
                $handle = "argyle-tender-diamond-" . $row['shape'] . "-" . $row['colour'] . "-" . $row['clarity'] . "-" . $row['sku'];
                $handle = strtolower($handle);
            } elseif (strtolower($row['type']) == "loose diamonds") {
                $handle = "";
                $handle = "argyle-pink-diamond-" . $row['shape'] . "-" . $row['colour'] . "-" . $row['clarity'] . "-" . $row['sku'];
                $handle = strtolower($handle);
            } else {
                $handle = "";
                $handle = str_replace(" ", "-", strtolower($row['product_title'])) . "-" . strtolower($row['sku']);
            }
            $handle = str_replace(["--", " "], "-", $handle);

            // Purchase Cost Calculation
            $purchase_cost = "";
            if (strtolower($row['type']) == "loose diamonds") {
                $purchase_cost = $row['purchase_cost_aud'] * $row['carat'];
                $purchase_cost = round($purchase_cost, 2);
            } else {
                $purchase_cost = $row['purchase_cost_aud'];
            }

            // Tags
            $tags = "";
            if ($row['tags'] != "") {
                $tags .= $row['tags'] . ", ";
            }
            if ($row['brand'] != "") {
                $tags .= $row['brand'] . ", ";
            }
            if ($row['colour'] != "") {
                $tags .= $row['colour'] . ", ";
            }
            if ($row['colour'] != "") {
                if (preg_match("/pp/i", strtolower($row['colour'])) > 0) {
                    $tags .= "PP - Purplish Pink, ";
                } elseif (preg_match("/pr/i", strtolower($row['colour'])) > 0) {
                    $tags .= "PR - Pink Rose, ";
                } elseif (preg_match("/pc/i", strtolower($row['colour'])) > 0) {
                    $tags .= "PC - Pink Champagne, ";
                } elseif (preg_match("/bl/i", strtolower($row['colour'])) > 0) {
                    $tags .= "BL - Blue, ";
                } elseif (preg_match("/pred/i", strtolower($row['colour'])) > 0) {
                    $tags .= "pRed - Pinkish Red, ";
                } else {
                    $tags .= "P - Pink, ";
                }
            }
            if ($row['shape'] != "") {
                $tags .= $row['shape'] . ", ";
            }
            if ($row['clarity'] != "") {
                $tags .= $row['clarity'] . ", ";
            }
            if ($row['collections'] != "") {
                $tags .= $row['collections'] . ", ";
            }
            if ($row['type'] != "") {
                $tags .= $row['type'] . ", ";
            }
            if ($row['main_metal'] != "") {
                $tags .= $row['main_metal'] . " Metal, ";
            }
            if ($row['preorder'] == 1) {
                $tags .= "Preorder, ";
            }
            if (strtolower($row['type']) == "loose diamonds") {
                if ($row['collections'] == "SKS") {
                    $tags .= "pkcertified";
                }
                if ($row['collections'] == "STN") {
                    $tags .= "argylecertified";
                }
            }
          
            $mediaInputs = [];          
           
            $productCheck = $this->getProductSingle($row['sku']);
            
            if(count($productCheck['data']['products']['edges'])>0)
            {               
                    $productData = [
                        'input' => [
                            'id'=>$productCheck['data']['products']['edges'][0]['node']['id'],
                            'title' =>$row['product_title'],
                            'descriptionHtml' => $row['description'],
                            'vendor' => $row['brand'],
                            'productType' => $row['type'],
                            'handle' => $handle,
                            'tags' => $tags,
                            'status' => $status
                        ]
                    ];
                    if(count($imageURL)>0)
                    {           
                    foreach ($imageURL as $imageUrls) {
                        $mediaInput = [
                            "alt" => $row['product_title'], 
                            "mediaContentType" => "IMAGE",
                            "originalSource" => $imageUrls 
                        ];
                
                        $mediaInputs[] = $mediaInput;
                    }
                                    
                        $productResponse = $this->updateProductWithImage($productData,$mediaInput);
                      
                    }
                    else{
                    
                        $productResponse = $this->updateProduct($productData);
                    } 
                    
                    $productSavedCheck = $productResponse['data']['productUpdate']['product'];
                    
            }
            else{
            //create a new product update its default variant and update it inventory level
            $productData = [
                'input' => [
                    'title' => $row['product_title'],
                    'descriptionHtml' => $row['description'],
                    'vendor' => $row['brand'],
                    'productType' => $row['type'],
                    'handle' => $handle,
                    'tags' => $tags,
                    'status' => $status
                ]
            ];
            if(count($imageURL)>0)
            {           
            foreach ($imageURL as $imageUrls) {
                $mediaInput = [
                    "alt" => $row['product_title'], 
                    "mediaContentType" => "IMAGE",
                    "originalSource" => $imageUrls 
                ];
        
                $mediaInputs[] = $mediaInput;
            }
                            
                $productResponse = $this->createProductWithVariantImageAndInventory($productData,$mediaInputs);
            }
            else{
              
                $productResponse = $this->createProductWithVariantAndInventory($productData);
            }              
            $productSavedCheck = $productResponse['data']['productCreate']['product'];
        }
                if(isset($productSavedCheck['variants']['edges'][0]['node']))
                {
                    
                    $productId = $productSavedCheck['id'];
                    
                    $variantId =$productSavedCheck['variants']['edges'][0]['node']['id'];
                    $locationId =$productSavedCheck['variants']['edges'][0]['node']['inventoryItem']['inventoryLevels']['edges'][0]['node']['location']['id'];
                               
                    $variantUpdateInput = [
                        "inventoryManagement"=> "SHOPIFY",
                        "sku" => $row['sku'],
                        "price" => $itemprice,
                        "id"=>$variantId,
                        "inventoryPolicy"=> "DENY",
                        "inventoryItem" => [
                            "cost" => $purchase_cost,
                        ],
                        ];
                      
                    $updateResponse=$this->updateProductVariant($variantUpdateInput);
                    if(isset($updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['inventoryLevels']['edges'][0]['node']))
                    {
                        $inventoryLevelId=$updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['inventoryLevels']['edges'][0]['node']['id'];
                        $inventoryId=$updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['id'];
                        // $quantity=$row['shopify_qty'];
                        $inventory_available_quantity=$this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][0]['quantity'];
                        $inventory_on_hand_quantity=$this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][1]['quantity'];
                        $inventory_commited_quantity=$this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][2]['quantity'];
                       
                        $quantity = $row['shopify_qty']-$inventory_available_quantity-$inventory_commited_quantity +1;
                       
                        if($quantity !=0)
                        {
                            $inventoryAdjustResponse =$this->adjustInventoryQuantity($inventoryId,$locationId,$quantity);
                            $success=true;
                          
                        }
                        $success=true;
                       
                    }
    
                }
               
            }
            

        if($success==true)
        {
            return "uploaded successfully";
        }
    }

    public function createProductWithVariantAndInventory($productData)
    {
    $mutation = '
    mutation CreateProductWithVariantAndInventory($input: ProductInput!) {
      productCreate(input: $input) {
        product {
          id
          title
          variants(first: 1) {
            edges {
              node {
                id
                title
                sku
                inventoryItem {
                  id
                  inventoryLevels(first: 1) {
                      edges {
                        node {
                          id
                          location {
                            id
                            name
                          }
                        }
                      }
                    }
                }
                
              }
            }
          }
        }
      
      }
    }';
        $productInput = $productData['input'];
        $variables = [
            'input' => $productInput
        ];
        return $this->makeGraphQLRequest($mutation, $variables);
    }
    public function createProductWithVariantImageAndInventory($productData, $mediaInputs)
    {
    $mutation = '
    mutation CreateProductWithVariantAndInventory($input: ProductInput!, $media: [CreateMediaInput!]) {
    productCreate(input: $input, media: $media) {
        product {
        id
        title
        variants(first: 1) {
            edges {
            node {
                id
                title
                sku
                inventoryItem {
                id
                inventoryLevels(first: 1) {
                    edges {
                    node {
                        id
                        location {
                        id
                        name
                        }
                    }
                    }
                }
                }
            }
            }
        }
        }
    }
    }';
        $productInput = $productData['input'];
        $variables = [
            'input' => $productInput,
            'media' => $mediaInputs
        ];
        return $this->makeGraphQLRequest($mutation, $variables);
    }
    public function updateProduct($productData)
    {
        $mutation = '
        mutation productUpdate($input: ProductInput!) {
            productUpdate(input: $input) {
                product {
                    id
                    title
                    variants(first: 1) {
                      edges {
                        node {
                          id
                          title
                          sku
                          inventoryItem {
                            id
                            inventoryLevels(first: 1) {
                              edges {
                                node {
                                  id
                                  location {
                                    id
                                    name
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
              userErrors {
                field
                message
              }
            }
          }';
            $productInput = $productData['input'];
            $variables = [
                'input' => $productInput
            ];
            return $this->makeGraphQLRequest($mutation, $variables);
    }
    public function updateProductWithImage($productData,$mediaInput)
    {
        $mutation = '
        mutation productUpdate($input: ProductInput!) {
            productUpdate(input: $input) {
                product {
                    id
                    title
                    variants(first: 1) {
                      edges {
                        node {
                          id
                          title
                          sku
                          inventoryItem {
                            id
                            inventoryLevels(first: 1) {
                              edges {
                                node {
                                  id
                                  location {
                                    id
                                    name
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
              userErrors {
                field
                message
              }
            }
          }';
           
            $productInput = $productData['input'];
            $variables = [
                'input' => $productInput,
                'media' => $mediaInput
            ];

            return $this->makeGraphQLRequest($mutation, $variables);
        }
   

    public function updateProductVariant($variantInput)
    {
        $mutation = '
            mutation productVariantUpdate($input: ProductVariantInput!) {
                  productVariantUpdate(input: $input) {
                    product {
                     title
                    }
                    productVariant {
                      sku
                      inventoryItem {
                        id
                        inventoryLevels(first: 1) {
                          edges {
                            node {
                              id
                              location {
                                id
                                name
                              }
                            }
                          }
                        }
                      }
                    }
                    userErrors {
                      field
                      message
                    }
                  }
                }';
                // Set the variables for the mutation
                $variables = [
                    'input' => $variantInput,
                ];
                // Make the GraphQL request
                return $this->makeGraphQLRequest($mutation, $variables);
    }



    public function adjustInventoryQuantity($inventoryId,$locationId,$quantity)
    {

        $mutation = '
                      mutation inventoryAdjustQuantities($input: InventoryAdjustQuantitiesInput!) {
                        inventoryAdjustQuantities(input: $input) {
                          inventoryAdjustmentGroup {
                            id
                          }
                          userErrors {
                            field
                            message
                          }
                        }
                      }
                ';
                
        $variables = [
            'input' => [
                'changes' => [
                    [
                        'delta' => intval($quantity),
                        'inventoryItemId' => $inventoryId,
                        'locationId' => $locationId,
                    ]
                ],
                'name' => 'available',
                'reason' => 'other'
            ]
        ];
        return $this->makeGraphQLRequest($mutation, $variables);
    }

    
    
    public function getInventoryLevelData($inventoryLevelId)
    {
        $query='{
            inventoryLevel(id: "'.$inventoryLevelId.'") {
              id
              quantities(names: ["available","on_hand", "committed"]) {
              name
              quantity
              }
              item {
                id
              }
              location {
                id
              }
              createdAt
              updatedAt
              canDeactivate
            }
        }';
       return $this->getData($query);
    }
    
    
    //for future use multiple variant cases
    public function addBulkVariantForProduct($productId,$variantData)
    {
        $mutation = '
        mutation ProductVariantsBulkCreate($productId: ID!, $variants: [ProductVariantsBulkInput!]!) {
            productVariantsBulkCreate(productId: $productId, variants: $variants) {
                product {
                   title
                }
                productVariants {
                   sku
                }
                userErrors {
                    field
                    message
                }
            }
        }
    ';
        $variables = [
            'productId' => $productId,
            'variants' => $variantData
        ];
        return $this->makeGraphQLRequest($mutation, $variables);

    }
    //product option needed for bulk option cases
    public function getProductOption($productId)
    {
        $query = '
    query GetProductOptions($productId: ID!) {
        product(id: $productId) {
            options {
                id
                name
                values
            }
        }
    }';
        $variables = [
            'productId' => $productId,
        ];
        $response = $this->makeGraphQLRequest($query, $variables);
        $productOptions = [];
        if (isset($response['data']['product']['options'])) {
            $options = $response['data']['product']['options'];
            foreach ($options as $option) {
                $optionId = $option['id'];
                $optionName = $option['name'];
                $values = $option['values'];
                $productOptions[] = [
                    'optionId' => $optionId,
                    'optionName' => $optionName,
                    'values' => $values,
                ];
            }
        }

        return $productOptions;
    }
    
    public function createProductVariant($variantInput)
    {
        $mutation = '
        mutation CreateVariant($input: ProductVariantInput!) {
            productVariantCreate(input: $input) {
                product {
                    id
                    title
                }
                productVariant {
                    id
                    sku
                    price
                }
                userErrors {
                    field
                    message
                }
            }
        }
    ';


        // Set the variables for the mutation
        $variables = [
            'input' => $variantInput,
        ];

        // Make the GraphQL request
        return $this->makeGraphQLRequest($mutation, $variables);
    }
    public function getProducts()
    {
        $query = 'products(first: 10) {
            edges {
                node {
                    id
                    title
                }
            }
        }';

        return $this->getData($query);
    }
    public function getProductSingle($sku)
    {
        
        $query = '{
            products(first: 1, query: "sku:'.$sku.'") {
              edges {
                node {
                  id
                  variants(first: 1) {
                    edges {
                      node {
                        id
                        weight
                        sku
                      }
                    }
                  }
                }
              }
            }
          }';

        return $this->getData($query);
    }


}


