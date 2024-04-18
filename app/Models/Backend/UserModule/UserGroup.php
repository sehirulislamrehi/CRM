<?php

namespace App\Models\Backend\UserModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    function user()
    {
        return $this->hasMany(User::class, 'user_group_id');
    }
}
