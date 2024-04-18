<?php

namespace App\Models\Backend\CustomerModule;

use App\Models\Backend\CommonModule\District;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\TicketingModule\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=[
      'name',
      'address',
      'phone',
      'thana_id',
      'district_id'
    ];

    function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class,'district_id');
    }

    function thana(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Thana::class,'thana_id');
    }

    function ticket(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class,'customer_id');
    }

    function customer_details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CustomerDetail::class,'customer_id');
    }
}
