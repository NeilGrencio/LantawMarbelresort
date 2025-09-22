<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    use HasFactory;

    protected $table = 'orders'; // table name in DB
    protected $primaryKey = 'orderID';
    public $timestamps = false; // since you’re using orderdate instead of created_at/updated_at

    protected $fillable = [
        'orderticket',
        'orderquantity',
        'orderdate',
        'total',
        'status',
        'guestID',
        'menuID',
    ];

    // ================= RELATIONSHIPS ================= //

    // Each order belongs to a guest
    public function guest()
    {
        return $this->belongsTo(GuestTable::class, 'guestID', 'guestID');
    }

    // Each order belongs to a menu item
    public function menu()
    {
        return $this->belongsTo(MenuTable::class, 'menuID', 'menuID');
    }
}
