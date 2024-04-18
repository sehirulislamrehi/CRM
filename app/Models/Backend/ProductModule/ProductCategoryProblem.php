<?php

namespace App\Models\Backend\ProductModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryProblem extends Model
{
    use HasFactory;

    protected $table = 'product_category_problems';
    protected $fillable = [
        'lang_code',
        'name',
        'is_active',
        'product_category_id'
    ];

    function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
