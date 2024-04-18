<?php

namespace App\Models\Backend\ProductModule;

use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\TicketingModule\TicketDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'product_category_id',
        'is_active',
        'is_home_service',
    ];

    use HasFactory;


    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_product_category');
    }

    public function product_category_note(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCategoryNote::class, 'product_category_id');
    }

    public function product_category_problem(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCategoryProblem::class, 'product_category_id');
    }

    public function ticket_detail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDetail::class, 'product_category_id');
    }

    public function service_center(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ServiceCenter::class,'product_category_service_center');
    }
}
