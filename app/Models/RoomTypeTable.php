<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypeTable extends Model
{
    protected $table = 'room_type';

    protected $primaryKey = 'roomtypeID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
         'description', 'roomtype', 'basecapacity', 'maxcapacity', 'price', 'extra', 'image', 'status', 'discountID', 'roomID',
    ];

    public function Discount(){
        return $this->belongsTo(DiscountTable::class, 'discountID');
    }

    public function rooms(){
        return $this->belongsTo(RoomTable::class, 'roomID');
    }
}
