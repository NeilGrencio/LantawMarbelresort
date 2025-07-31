<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTable extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'userID';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'status'
    ];

    protected $hidden   = ['password'];
}


