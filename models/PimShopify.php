<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class PimShopify extends Model
{
    protected $table='pim_shopify';
    protected $fillable = ['sku', 'product_title', 'description', 'brand', 'type', 'shopify_qty', 'deletion', 'stone_price_retail_aud', 'retail_aud', 'shape', 'colour', 'clarity', 'tags', 'collections', 'main_metal', 'preorder', 'collections_2', 'purchase_cost_aud', 'status','image1',
    'image2', 
    'image3',
    'image4', 
    'image5',
    'image6',
    'packaging_image',
    'specifications'];
}
