<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRTable extends Model
{
    protected $table = "qrcodes";
    protected $primaryKey = "qrID";
    public $keyType = 'int';
    public $timestamps = false;
    public $fillable = [
        'qrcode', 'accessdate', 'amenityID', 'guestID'
    ];

    public function Amenity(){
        return $this->belongsTo(AmenityTable::class, 'amenityID', 'amenityID');
    }

    public function Guest(){
        return $this->belongsTo(GuestTable::class, 'guestID', 'guestID');
    }
}
