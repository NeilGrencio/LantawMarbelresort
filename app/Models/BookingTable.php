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
        'guestamount', 'childguest', 'adultguest', 'totalprice', 'bookingcreated', 'bookingend', 'bookingstart', 'status', 'guestID', 'amenityID','booking_type'
    ];

    public function Guest(){
        return $this->belongsTo(GuestTable::class, 'guestID');
    }

    public function Amenity(){
        return $this->belongsTo(AmenityTable::class, 'amenityID');
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
        return $this->hasOne(BillingTable::class, 'bookingID', 'bookingID');
    }
    public function menuBookings()
    {
        return $this->hasMany(MenuBookingTable::class, 'booking_id', 'bookingID');
    }
}
