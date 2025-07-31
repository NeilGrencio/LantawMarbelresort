<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountTable extends Model
{
    protected $table = 'discount';
    protected $primaryKey = 'discountID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'name', 'amount', 'status'
    ];
}
