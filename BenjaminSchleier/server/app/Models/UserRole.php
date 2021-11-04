<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'role_id',
        'role_name',
        'created_at',
        'updated_at',
    ];
}
