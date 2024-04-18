<?php

namespace App\Models\Backend\CommonModule;

use App\Models\Backend\CustomerModule\Customer;
use App\Models\Backend\TicketingModule\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    function thana(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Thana::class, 'district_id');
    }

    function customer(){
        return $this->hasMany(Customer::class,'district_id');
    }

    function ticket(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class,'district_id');
    }
}
