<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $table = 'users';
    public $timestamps = false;
    protected $fillable = [
        'username',
        'password',
        'status'
    ];

    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }
    public function guest()
    {
        return $this->hasOne(GuestTable::class, 'userID','userID');
    }
       public function staff()
    {
        return $this->hasOne(StaffTable::class, 'userID'); // foreign key on staff table
    }
}
