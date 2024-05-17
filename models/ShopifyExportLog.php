<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyExportLog extends Model
{
    protected $table='shopify_export_log';
    protected $fillable = ['exported_by','sku','exported_type'];
}
