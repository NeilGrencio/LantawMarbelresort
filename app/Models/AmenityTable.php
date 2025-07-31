<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityTable extends Model
{
    protected $table = 'amenities';
    public $primarykey = 'amenityID';
    protected $keytype = 'int';
    public $timestamps = false;

    protected $fillable = [
        'amenityname', 'description', 'adultprice', 'childprice', 'image', 'status'    
    ];
}
