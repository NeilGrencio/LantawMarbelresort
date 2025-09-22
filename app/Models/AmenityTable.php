<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityTable extends Model
{
    protected $table = 'amenities';
    protected $primaryKey = 'amenityID'; // <- Corrected
    protected $keyType = 'int';          // Optional: if your key is integer
    public $timestamps = false;

    protected $fillable = [
        'amenityname', 'description', 'adultprice', 'childprice', 'image', 'status'
    ];
}
