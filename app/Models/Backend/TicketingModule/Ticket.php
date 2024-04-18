<?php

namespace App\Models\Backend\TicketingModule;

use App\Models\Backend\CommonModule\Channel;
use App\Models\Backend\CommonModule\District;
use App\Models\Backend\CommonModule\Thana;
use App\Models\Backend\CustomerModule\Customer;
use App\Models\Backend\UserModule\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;

/**
 * @mixin Builder
 */
class Ticket extends Model
{
    use HasFactory;

    function ticket_detail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDetail::class, 'ticket_id');
    }

    function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    function thana(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function channel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Channel::class,'channel_id');
    }


}
