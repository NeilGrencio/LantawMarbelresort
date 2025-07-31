<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatTable extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'chatID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'chat', 'datesent', 'reply', 'datereplied', 'status', 'guestID', 'staffID'
    ];

    public function guest(){
        return $this->belongsto(GuestTable::class, 'guestID');
    }

    public function staff(){
        return $this->belongsto(StaffTable::class, 'staffID');
    }
}
    