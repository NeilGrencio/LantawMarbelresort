<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusionTable extends Model
{
    use HasFactory;
    
    protected $table = 'inclusions';
    protected $primaryKey = 'inclusionID';
    public $timestamps = true;

    protected $fillable = [
        'roomtypeID',
        'amenityID',
        'menuID',
    ];

    // Relationships
    public function roomtype()
    {
        return $this->belongsTo(RoomTypeTable::class, 'roomtypeID', 'roomtypeID');
    }

    public function amenity()
    {
        return $this->belongsTo(AmenityTable::class, 'amenityID', 'amenityID');
    }

    public function menu()
    {
        return $this->belongsTo(MenuTable::class, 'menuID', 'menuID');
    }
}
