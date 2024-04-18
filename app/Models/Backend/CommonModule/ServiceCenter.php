<?php

namespace App\Models\Backend\CommonModule;

use App\Models\Backend\ProductModule\ProductCategory;
use App\Models\Backend\TicketingModule\Ticket;
use App\Models\Backend\TicketingModule\TicketDetail;
use App\Models\Backend\UserModule\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    use HasFactory;

    function thana(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Thana::class, 'service_center_thana');
    }

    function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'service_center_user');
    }

    function ticket_detail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDetail::class,'service_center_id');
    }

    function business_unit(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(BusinessUnit::class,'business_unit_service_center');
    }

    function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class,'product_category_service_center');
    }
}
