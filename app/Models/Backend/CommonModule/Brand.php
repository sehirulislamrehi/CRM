<?php

namespace App\Models\Backend\CommonModule;

use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\TicketingModule\TicketDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public function businessUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    public function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'brand_product_category');
    }

    public function ticket_detail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDetail::class,'brand_id');
    }

}
