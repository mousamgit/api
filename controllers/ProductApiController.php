<?php

namespace controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

use models\Products;

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
            // dd($responseData);
            return $responseData;
            // Process the response data as needed
        } else {
            echo "Error: HTTP status code {$statusCode}\n";
            echo "Response: {$response}\n";
        }
    }
    //view products
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


   
    public function createProduct()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $exporting_rows = $data['exportRows'];
        
       
        foreach ($exporting_rows as $key => $row) {
            
            $imageURL = $this->getImageURLs($row);
            $status = $this->getStatus($row);
            $command = $this->getCommand($row, $status);
            $itemprice = $this->getItemPrice($row);
            $handle = $this->getHandle($row);
            $purchase_cost = $this->getPurchaseCost($row);
            $tags = $this->getTags($row);

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
                            'status' => $status, 
                            'published'=> true,
                            'collectionsToJoin'=> $this->getCollections(),
                            'metafields'=> [
                              'description'=>$row['description'],
                              'key'=> $row['sku'],
                              'namespace'=> $row['product_title'],
                              'type'=> $row['type']
                            ],
                            'seo'=> [
                              'description'=>$row['description'],
                              'title'=> $row['product_title']
                            ],
                        ]
                        ];
                    if(count($imageURL)>0)
                    {           
                    foreach ($imageURL as $imageUrls) {
                        $mediaInput = [
                            'alt' => $row['product_title'], 
                            'mediaContentType' => 'IMAGE',
                            'originalSource' => $imageUrls 
                        ];
                
                        $mediaInputs[] = $mediaInput;
                    }
                                    
                        $productResponse = $this->updateProductWithImage($productData,$mediaInput);
                      
                      
                    }
                    else{
                    
                        $productResponse = $this->updateProduct($productData);
                    } 
                   dd($productResponse);
                  
                    $productSavedCheck = $productResponse['data']['productUpdate']['product'];
                    
            }
            else{
            $productData = [
                'input' => [
                    'title' => $row['product_title'],
                    'descriptionHtml' => $row['description'],
                    'vendor' => $row['brand'],
                    'productType' => $row['type'],
                    'handle' => $handle,
                    'tags' => $tags,
                    'status' => $status,
                    'published'=>true,
                    'collectionsToJoin'=> $this->getCollections(),
                    'metafields'=> [
                      'description'=>$row['description'],
                      'key'=> $row['sku'],
                      'namespace'=> $row['product_title'],
                      'type'=> $row['type']
                    ],
                    'seo'=> [
                      'description'=>$row['description'],
                      'title'=> $row['product_title']
                    ],
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
                       
                        $quantity = $row['shopify_qty']-$inventory_available_quantity-$inventory_commited_quantity;
                       
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
            echo "uploaded successfully";
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
    public function getCollections()
    {
        
        $query = 'query {
          collections(first: 5) {
            edges {
              node {
                id
                title
                handle
                updatedAt
                sortOrder
              }
            }
          }
        }';

      $response = $this->getData($query);
      $collections =[];
      foreach ($response['data']['collections']['edges'] as $edge) {
          $collections[] =  $edge['node']['id'];
      }
  
      return $collections;
    }
    function getAllPublications() {
      $query = '
      query publications {
        publications(first: 5) {
          edges {
            node {
              id
              name
              supportsFuturePublishing
              app {
                id
                title
                description
                developerName
              }
            }
          }
        }
      }
      ';
  
      $response = $this->getData($query);
      $publications = [];
      foreach ($response['data']['publications']['edges'] as $edge) {
          $publications =  $edge['node']['id'];
      }
  
      return $publications;
      
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


        //logic implementations functions

        private function getImageURLs($row)
        {
            $imageURL = [];
            $imageFields = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'packaging_image'];
            foreach ($imageFields as $field) {
                if (!empty($row[$field])) {
                    $imageURL[] = $row[$field];
                }
            }
            return $imageURL;
        }

        private function getStatus($row)
        {
            $collections = strtolower($row['collections_2']);
            if (preg_match("/steve|discontinued|wholesale_only/i", $collections)) {
                return "DRAFT";
            }
            return "ACTIVE";
        }

        private function getCommand($row, $status)
        {
            if ($row['shopify_qty'] > 0) {
                if ($status == "ACTIVE" || $status == "DRAFT") {
                    return "MERGE";
                }
                if ($row['deletion'] == 1) {
                    return "DELETE";
                }
            }
            return "DELETE";
        }

        private function getItemPrice($row)
        {
            if (strtolower($row['type']) == "loose diamonds") {
                return $row['stone_price_retail_aud'];
            }
            return $row['retail_aud'];
        }

        private function getHandle($row)
        {
            $sku = $row['sku'];
            $shape = $row['shape'];
            $colour = $row['colour'];
            $clarity = $row['clarity'];
            $product_title = $row['product_title'];

            if (substr($sku, 0, 3) == "TDR" || substr($sku, 0, 3) == "TPR") {
                $handle = "argyle-tender-diamond-$shape-$colour-$clarity-$sku";
            } elseif (strtolower($row['type']) == "loose diamonds") {
                $handle = "argyle-pink-diamond-$shape-$colour-$clarity-$sku";
            } else {
                $handle = str_replace(" ", "-", strtolower($product_title)) . "-$sku";
            }
            return str_replace(["--", " "], "-", strtolower($handle));
        }

        private function getPurchaseCost($row)
        {
            if (strtolower($row['type']) == "loose diamonds") {
                return round($row['purchase_cost_aud'] * $row['carat'], 2);
            }
            return $row['purchase_cost_aud'];
        }

        private function getTags($row)
        {
            $tags = "";
            $tags .= !empty($row['tags']) ? $row['tags'] . ", " : "";
            $tags .= !empty($row['brand']) ? $row['brand'] . ", " : "";
            $tags .= !empty($row['colour']) ? $row['colour'] . ", " : "";
            $tags .= preg_match("/pp|pr|pc|bl|pred/i", strtolower($row['colour'])) ?
                strtoupper(substr($row['colour'], 0, 2)) . " - " . ucfirst(strtolower($row['colour'])) . ", " : "";
            $tags .= !empty($row['shape']) ? $row['shape'] . ", " : "";
            $tags .= !empty($row['clarity']) ? $row['clarity'] . ", " : "";
            $tags .= !empty($row['collections']) ? $row['collections'] . ", " : "";
            $tags .= !empty($row['type']) ? $row['type'] . ", " : "";
            $tags .= !empty($row['main_metal']) ? $row['main_metal'] . " Metal, " : "";
            $tags .= $row['preorder'] == 1 ? "Preorder, " : "";
            if (strtolower($row['type']) == "loose diamonds") {
                $tags .= $row['collections'] == "SKS" ? "pkcertified" : "";
                $tags .= $row['collections'] == "STN" ? "argylecertified" : "";
            }
            return $tags;
        }

   


}


