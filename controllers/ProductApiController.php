<?php

namespace controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

use models\Products;
use models\PimShopify;
use models\ShopifyExportLog;
use Exception;

class ProductApiController
{

    private $storeUrl;
    private $accessToken;

    public function __construct()
    {
        $this->storeUrl = "sga-development.myshopify.com";
        // sga-dev-token
         $this->accessToken = "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";  
        // pink-kimberley
        // $this->accessToken = "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";    
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


    public function getCategory($product)
    {
          $category = "Apparel & Accessories > Jewelry";
          if ( strtolower($product['type']) == "rings") { $category .= " > Rings";}
          if ( strtolower($product['type']) == "earrings") { $category .= " > Earrings";}
          if ( strtolower($product['type']) == "bracelets") { $category .= "Bracelets";}
          if ( strtolower($product['type']) == "necklaces") { $category .= " > Necklaces";}
          if ( strtolower($product['type']) == "loose diamonds") { $category .= " > Loose Stones";}
          return $category;
    }
    public function createProduct()
    {
    $data = json_decode(file_get_contents("php://input"), true);
    if(isset($data['exportRows']) && is_array($data['exportRows'])) {
        $exporting_rows = $data['exportRows'];
        $sku = array_column($exporting_rows, 'sku');
        $products = Products::whereIn('sku', $sku)->get();
        if($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product->status = 'pending';
                $product->sku = $product['sku'];
                PimShopify::create($product->toArray());
            }
            echo "Products processed successfully.";
        } else {
            echo "No products found with the provided SKUs.";
        }
      } else {
        echo "'exportRows' missing or not an array in the received data.";
      }
    }
   

    public function processProduct($product)
    {      
        error_reporting(E_ALL);
        ini_set('display_errors', '1');  
           
        $imageURL = $this->getImageURLs($product);
        $status = $this->getStatus($product);
        $command = $this->getCommand($product, $status);
        $itemprice = $this->getItemPrice($product);
        $handle = $this->getHandle($product);
        $purchase_cost = $this->getPurchaseCost($product);
        $tags = $this->getTags($product);
        $category = $this->getCategory($product);
        $mediaInputs = [];
        $productCheck = $this->getProductSingle($product['sku']);
        if ($command == 'MERGE') {
            $process_type ='update';
            if (!empty($productCheck['data']['products']['edges'])) {                     
                $metaFields = $productCheck['data']['products']['edges'][0]['node']['metafields']['edges'];
                $sp_id = ''; 
                $cc_id = '';
                
                foreach($metaFields as $mfield) {
                    if ($mfield['node']['key'] == 'Specifications') {
                        $sp_id = $mfield['node']['id'];
                    }
                    if ($mfield['node']['key'] == 'centrecolour') {
                        $cc_id = $mfield['node']['id'];
                    }
                }
    
                $metaFieldValue = [
                    [
                        'id' => $sp_id ?: null,
                        'value' => $product['specifications'],
                        'type' => 'multi_line_text_field'
                    ],
                    [
                        'id' => $cc_id ?: null,
                        'value' => $product['colour'],
                        'type' => 'single_line_text_field'
                    ]
                ];
    
                $productData = [
                    'input' => [
                        'id' => $productCheck['data']['products']['edges'][0]['node']['id'],
                        'title' => $product['product_title'],
                        'descriptionHtml' => $product['description'],
                        'vendor' => $product['brand'],
                        'productType' => $product['type'],
                        'handle' => $handle,
                        'tags' => $tags,
                        'status' => $status,
                        'publications' => $this->getAllPublications(),
                        'published' => true,
                        'metafields' => $metaFieldValue,
                    ]
                ];
    
                if (count($imageURL) > 0) {
                    foreach ($imageURL as $imageUrls) {
                        $mediaInput = [
                            'alt' => $product['product_title'],
                            'mediaContentType' => 'IMAGE',
                            'originalSource' => $imageUrls
                        ];
                        $mediaInputs[] = $mediaInput;
                    }
                    $productResponse = $this->updateProductWithImage($productData, $mediaInputs);
                } else {
                    $productResponse = $this->updateProduct($productData);
                }
    
                $productSavedCheck = $productResponse['data']['productUpdate']['product'];
                echo "Updated \n";
                $log_data = [
                    'sku' => $product['sku'],
                    'exported_type' => 'update',
                    'exported_by' => 'mousam'
                ];
                ShopifyExportLog::create($log_data);
            } else {
               
                $productData = [
                    'input' => [
                        'title' => $product['product_title'],
                        'descriptionHtml' => $product['description'],
                        'vendor' => $product['brand'],
                        'productType' => $product['type'],
                        'handle' => $handle,
                        'tags' => $tags,
                        'status' => $status,
                        'publications' => $this->getAllPublications(),
                        'published' => true,
                        'metafields' => [
                            [
                                'key' => 'Specifications',
                                'namespace' => 'custom',
                                'value' => $product['specifications'], 
                                'type' => 'multi_line_text_field'
                            ],
                            [
                                'key' => 'centrecolour',
                                'namespace' => 'custom',
                                'value' => $product['colour'],
                                'type' => 'single_line_text_field'
                            ]
                        ],
                        'seo' => [
                            'description' => $product['description'],
                            'title' => $product['product_title']
                        ],
                    ]
                ];
    
                if (count($imageURL) > 0) {
                    foreach ($imageURL as $imageUrls) {
                        $mediaInput = [
                            "alt" => $product['product_title'],
                            "mediaContentType" => "IMAGE",
                            "originalSource" => $imageUrls
                        ];
                        $mediaInputs[] = $mediaInput;
                    }
                    $productResponse = $this->createProductWithVariantImageAndInventory($productData, $mediaInputs);
                } else {
                    $productResponse = $this->createProductWithVariantAndInventory($productData);
                }
    
                $productSavedCheck = $productResponse['data']['productCreate']['product'];
                $process_type ='insert';
                echo "New \n";
                $log_data = [
                    'sku' => $product['sku'],
                    'exported_type' => 'new',
                    'exported_by' => 'mousam'
                ];
                ShopifyExportLog::create($log_data);
            }
            
            echo "Product " . $product['sku'] . " Uploaded \n";
    
            if (isset($productSavedCheck['variants']['edges'][0]['node'])) {
                try {
                    $productId = $productSavedCheck['id'];
                    $variantId = $productSavedCheck['variants']['edges'][0]['node']['id'];
                    $locationId = $productSavedCheck['variants']['edges'][0]['node']['inventoryItem']['inventoryLevels']['edges'][0]['node']['location']['id'];
                    
                    
                    $variantUpdateInput = [
                        "inventoryManagement" => "SHOPIFY",
                        "sku" => $product['sku'],
                        "price" => $itemprice,
                        "id" => $variantId,
                        "inventoryPolicy" => "DENY",
                        "inventoryItem" => [
                            "cost" => $purchase_cost,
                        ],
                    ];
                   
                    $updateResponse = $this->updateProductVariant($variantUpdateInput);
                    if (isset($updateResponse['errors'])) {
                      throw new \Exception("Variant update failed: " . json_encode($updateResponse['errors']));
                    }
                    
                    if (isset($updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['inventoryLevels']['edges'][0]['node'])) {
                        $inventoryLevelId = $updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['inventoryLevels']['edges'][0]['node']['id'];
                        $inventoryId = $updateResponse['data']['productVariantUpdate']['productVariant']['inventoryItem']['id'];
                        $inventory_available_quantity = $this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][0]['quantity'];
                        $inventory_on_hand_quantity = $this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][1]['quantity'];
                        $inventory_commited_quantity = $this->getInventoryLevelData($inventoryLevelId)['data']['inventoryLevel']['quantities'][2]['quantity'];
                        $quantity = $product['shopify_qty'] - $inventory_available_quantity - $inventory_commited_quantity;
    
                        if ($quantity != 0) {
                            $inventoryAdjustResponse = $this->adjustInventoryQuantity($inventoryId, $locationId, $quantity);
                           
                            if (isset($inventoryAdjustResponse['errors'])) {
                              echo "inventory error \n";
                              throw new \Exception("Inventory update failed: " . json_encode($inventoryAdjustResponse['errors']));
                            }
                            $success = true;
                        }
                        $success = true;
                        $st_value='exported';
                    }
                    $update_status_2 = true;
                } catch (\Exception $e) {
                
                  if($process_type =='insert')
                  {
                    $this->deleteProduct($productId);
                    echo "deleted ".$product['product_title']. "as it failes to update variant \n";
                    $st_value='failed';
                  }            
                  else{
                    echo $product['product_title']. " failes to update variant \n";
                    $st_value='failed';
                  } 
                }
            }
    
            $this->updateProductStatus($product, $st_value);
            echo "Product " . $product['product_title'] . " Uploaded \n";
            $success = true;
    
            if ($success == true) {
                echo "All Uploaded successfully \n";
            } else {
                echo "No Pending Products \n";
            }
        } elseif ($command == 'DELETE') {
            if (!empty($productCheck['data']['products']['edges'])) { 
                $productId = $productCheck['data']['products']['edges'][0]['node']['id'];
                $this->deleteProduct($productId);
                echo "deleted " . $product['sku'] . "\n";
                $log_data = [
                    'sku' => $product['sku'],
                    'exported_type' => 'delete',
                    'exported_by' => 'mousam'
                ];
                ShopifyExportLog::create($log_data);
                $this->updateProductStatus($product, 'exported');
            } else {
              
                $log_data = [
                    'sku' => $product['sku'],
                    'exported_type' => 'no_item',
                    'exported_by' => 'mousam'
                ];
                echo "no item in shopify \n";
                ShopifyExportLog::create($log_data);
                $this->updateProductStatus($product, 'exported');
            }
        }           
    }
    
      
    private function deleteProduct($productId) {
     
      $mutation = '
      mutation productDelete($input: ProductDeleteInput!) {
        productDelete(input: $input) {
          deletedProductId
        }
      }
      ';
      $variables = [
        'input' => [
            'id' =>  $productId
        ]
    ];
     
      return $this->makeGraphQLRequest($mutation, $variables);
  }



    private function updateProductStatus($product, $status)
    {
          $product->status = $status;
          $product->save();
    }

    private function updateProductAndVariantId($sku,$productId, $variantId)
    {
          $pim = Products::where('sku',$sku)->update(['product_id'=>$productId,'variant_id'=>$variantId]);
    }

    public function getIdFromGid($gid)
    {
      
      $parts = explode('/', $gid);

      $id = end($parts);

      return $id;

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
    
    public function getMetaFields()
    {
        $query = '{
            metafieldDefinitions(first: 250, ownerType: PRODUCT) {
                edges {
                    node {
                        name
                        id
                    }
                }
            }
        }';

    return $this->getData($query);
}
public function getCategories()
    {
        $query = 'query {
          collection(title: "Necklaces") {
            edges {
              node {
                id
                name
              }
            }
          }
        }';

    return $this->getData($query);
}
public function getAllPublications()
    {
        $query = 'query publications {
          publications(first: 10) {
            edges {
              node {
                id
                name
                supportsFuturePublishing
                app {
                  id
                  title
                  description
                }
              }
            }
          }
        }';
        $response = $this->getData($query);
        $publications = array_map(function($publication) {
          return [
            'publicationId' => $publication['node']['id']
          ];
        }, $response['data']['publications']['edges']);
        
        $publications = array_values($publications);
        return $publications;
        
    
    return $publications;
}

    public function getProductSingle($sku)
    {
        
        $query = '{
            products(first: 1, query: "sku:'.$sku.'") {
              edges {
                node {
                  id,
                  metafields(first: 10) {
                    edges {
                      node {
                        id
                        namespace
                        key
                        value
                      }
                    }
                  }
                  variants(first: 1) {
                    edges {
                      node {
                        id
                        weight
                        sku
                      }
                    }
                  }
                  productCategory {
                    productTaxonomyNode {
                      id
                      name
                      isRoot
                      isLeaf
                      fullName
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
        try{
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
    catch(Exception $e)
    {
      echo $e;
      return [];
    }
      
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
              metafields(first: 10) {
                edges {
                  node {
                    id
                    namespace
                    key
                    value
                  }
                }
              }
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
          $purchase_cost = "";
          if( strtolower($row['type']) == "loose diamonds" ) { 
            $purchase_cost = $row['purchase_cost_aud'] * $row['carat']; 
            $purchase_cost = round($purchase_cost,2); } 
            else{
               $purchase_cost = $row['purchase_cost_aud']; 
              }
              return $purchase_cost;
        }

        private function getTags($row)
        {
            $tags = "";
            $tags .= $row['tags'] ? $row['tags'] . ", " : "";
            $tags .= $row['brand'] ? $row['brand'] . ", " : "";
            $tags .= $row['colour'] ? $row['colour'] . ", " : "";
            $tags .= preg_match("/pp|pr|pc|bl|pred/i", strtolower($row['colour'])) ?
                strtoupper(substr($row['colour'], 0, 2)) . " - " . ucfirst(strtolower($row['colour'])) . ", " : "";
            $tags .= $row['shape'] ? $row['shape'] . ", " : "";
            $tags .= $row['clarity'] ? $row['clarity'] . ", " : "";
            $tags .= $row['collections'] ? $row['collections'] . ", " : "";
            $tags .= $row['type'] ? $row['type'] . ", " : "";
            $tags .= $row['main_metal'] ? $row['main_metal'] . " Metal, " : "";
            $tags .= $row['preorder'] == 1 ? "Preorder, " : "";
            if (strtolower($row['type']) == "loose diamonds") {
                $tags .= $row['collections'] == "SKS" ? "pkcertified" : "";
                $tags .= $row['collections'] == "STN" ? "argylecertified" : "";
            }
            return $tags;
        }

   


}


