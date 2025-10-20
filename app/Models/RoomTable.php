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
         'roomnum', 'status', 'roomtypeID'
    ];

    public function roomtype(){
        return $this->belongsTo(RoomTypeTable::class, 'romtypeID');
    }
}
