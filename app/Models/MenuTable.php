<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuTable extends Model
{
    protected $table = "menu";
    protected $primaryKey = "menuID";
    public $keyType = 'int';
    public $timestamps = false;
    public $fillable = [
        'menuname', 'itemtype', 'image', 'price', 'status'
    ];
}
