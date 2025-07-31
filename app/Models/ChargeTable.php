<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeTable extends Model
{
    protected $table = 'additionalcharges';
    protected $primaryKey = 'chargeID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'amount', 'chargedescription',
    ];
}
