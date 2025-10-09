<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityTable extends Model
{
    protected $table = 'amenities';
    protected $primaryKey = 'amenityID';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'amenityname', 'description', 'capacity', 'adultprice', 'childprice', 'image', 'status'
    ];
}
