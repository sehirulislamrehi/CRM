<?php

namespace App\Models\Backend\CustomerModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'alternative_phone',
        'present_address',
        'permanent_address',
        'nid',
        'gender'
    ];

    function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
