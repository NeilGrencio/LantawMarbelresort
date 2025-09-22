<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CottageBookTable extends Model
{
    protected $table = 'cottagebook';
    protected $primaryKey = 'cottagebookID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'bookingID', 'cottageID','bookingDate'
    ];

    public function Booking()
    {
        return $this->belongsTo(BookingTable::class, 'bookingID');
    }
    public function Cottage()
    {
        return $this->belongsTo(CottageTable::class, 'cottageID');
    }
}
