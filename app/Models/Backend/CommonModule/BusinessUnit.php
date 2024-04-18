<?php

namespace App\Models\Backend\CommonModule;

use App\Models\Backend\TicketingModule\TicketDetail;
use App\Models\Backend\UserModule\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUnit extends Model
{
    use HasFactory;

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class, 'business_unit_id');
    }

    function ticket_details(): HasMany
    {
        return $this->hasMany(TicketDetail::class,'business_unit_id');
    }

    function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'business_unit_user');
    }

    function service_center(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ServiceCenter::class,'business_unit_service_center');
    }

}
