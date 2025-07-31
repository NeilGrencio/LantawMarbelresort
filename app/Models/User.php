<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   protected $table = 'users';

    protected $fillable = [
        'username', 'password', 'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
