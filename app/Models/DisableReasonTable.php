<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisableReasonTable extends Model
{
    protected $table = 'disabled_reasons';
    protected $primaryKey = 'reasonID';
    public $timestamps = true;

    protected $fillable = [
        'roomID',
        'amenityID',
        'cottageID',
        'reason',
        'reported_by',
        'reported_date',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function room(){
        return $this->belongsTo(RoomTable::class, 'roomID');
    }

    public function cottage()
    {
        return $this->belongsTo(CottageTable::class, 'cottageID');
    }

    public function amenity()
    {
        return $this->belongsTo(AmenityTable::class, 'amenityID');
    }

    public function staff(){
        return $this->belongsTo(StaffTable::class, 'staffID');
    }
}
