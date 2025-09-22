<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuBookingTable extends Model
{
    protected $table = 'menu_bookings';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'menu_id',
        'booking_id',
        'quantity',
        'price',
        'status',
    ];

    // Relationships
    public function menu()
    {
        return $this->belongsTo(MenuTable::class, 'menu_id', 'menuID');
    }

    public function booking()
    {
        return $this->belongsTo(BookingTable::class, 'booking_id', 'bookingID');
    }
}
