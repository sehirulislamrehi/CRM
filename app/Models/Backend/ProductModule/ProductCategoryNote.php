<?php

namespace App\Models\Backend\ProductModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'is_active',
        'is_home_service',
        'product_category_id',
    ];

    function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
