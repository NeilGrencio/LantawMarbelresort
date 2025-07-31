<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckTable extends Model
{
    protected $table = 'checkincheckout';
    protected $primaryKey = 'checkID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'date', 'status', 'guestID', 'bookingID'
    ];

    public function Guest(){
        return $this->belongsTo(GuestTable::class, 'guestID');
    }

    public function Booking(){
        return $this->belongsTo(BookingTable::class, 'bookingID');
    }
}
