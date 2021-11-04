<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_infos';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'pin',
        'name',
        'token',
        'email',
        'avatar',
        'password',
        'user_name',
        'user_role',
        'created_at',
        'updated_at',
        'is_verified',
        'registered_at',
    ];
}
