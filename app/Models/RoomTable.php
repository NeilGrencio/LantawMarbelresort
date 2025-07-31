<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTable extends Model
{
    protected $table = 'rooms';

    protected $primaryKey = 'roomID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'roomnum', 'description', 'roomtype', 'roomcapacity', 'price', 'image', 'status'
    ];
}
