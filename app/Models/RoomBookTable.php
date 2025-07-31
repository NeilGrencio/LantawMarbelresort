<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBookTable extends Model
{
    protected $table = 'roombook';
    protected $primaryKey = 'roombookID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'bookingID', 'roomID'
    ];

    public function Booking()
    {
        return $this->belongsTo(BookingTable::class, 'bookingID');
    }
    public function Room()
    {
        return $this->belongsTo(RoomTable::class, 'roomID');
    }
}
