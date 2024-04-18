<?php

namespace App\Models\Backend\TicketingModule;

use App\Models\Backend\CommonModule\Brand;
use App\Models\Backend\CommonModule\BusinessUnit;
use App\Models\Backend\CommonModule\ServiceCenter;
use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\TicketingModule\Traits\TicketDetailsHelperTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;
    use TicketDetailsHelperTrait;

    function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    function service_center(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class, 'service_center_id');
    }

    function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    function business_unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    function ticket_details_problem(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDetailProblem::class, 'ticket_details_id');
    }

}
