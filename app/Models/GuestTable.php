<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestTable extends Model
{
    protected $table = 'guest';

    protected $primaryKey = 'guestID';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'firstname', 'lastname', 'mobilenum', 'email', 'gender', 'birthday', 'validID', 'role', 'avatar', 'userID'
    ];

    public function Users(){
        return $this->belongsTo(User::class, 'userID');
    }
}
