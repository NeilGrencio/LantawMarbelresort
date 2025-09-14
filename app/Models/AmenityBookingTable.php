<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityBookingTable extends Model
{
    use HasFactory;

    protected $table = 'amenity_booking_table';

    protected $fillable = [
        'amenity_id',
        'booking_id',
        'date',
        'status',
    ];

    // Relationships (optional)
    public function amenity()
    {
        return $this->belongsTo(AmenityTable::class, 'amenityID');
    }

    public function booking()
    {
        return $this->belongsTo(BookingTable::class, 'booking_id');
    }
}
