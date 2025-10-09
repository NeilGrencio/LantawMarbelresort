<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'userID';
    public $timestamps = false;
    protected $fillable = [
        'username',
        'password',
        'status',
        'fcm_token'
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
