<?php

namespace App\Models\Backend\TicketingModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetailProblem extends Model
{
    use HasFactory;

    public function ticket_details(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TicketDetail::class,'ticket_details_id');
    }
}
