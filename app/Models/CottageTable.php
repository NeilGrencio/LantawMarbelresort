<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CottageTable extends Model
{
    protected $table = 'cottages';

    protected $primaryKey = 'cottageID';
    public $incrementing = true;
    public $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'cottagename', 'capacity', 'image', 'price', 'status', 'amenityID'
    ];

    public function amenity(){
        return $this->belongsTo(AmenityTable::class, 'amenityID');
    }

}
