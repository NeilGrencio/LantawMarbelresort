<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTable extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'guestamount', 'childguest', 'adultguest', 'totalprice', 'bookingcreated', 'bookingend', 'bookingstart', 'status', 'guestID'
    ];
    public function Guest(){
        return $this->belongsTo(GuestTable::class, 'guestID');
    }
    public function AmenityBook(){
        return $this->hasMany(AmenityBookingTable::class, 'booking_id');
    }
    public function roomBookings()
    {
        return $this->hasMany(RoomBookTable::class, 'bookingID');
    }
    public function cottageBookings()
    {
        return $this->hasMany(CottageBookTable::class, 'bookingID');
    }

    public function billing()
    {
        return $this->hasMany(BillingTable::class, 'bookingID', 'bookingID');
    }
}
