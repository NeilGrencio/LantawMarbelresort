<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingTable extends Model
{
    protected $table = 'billing';
    protected $primaryKey = 'billingID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'totalamount', 'datebilled', 'status', 'bookingID', 'orderID', 'amenityID', 'chargeID', 'discountID', 'guestID'
    ];

    public function payments()
    {
        return $this->hasMany(PaymentTable::class, 'billingID', 'billingID');
    }

    public function charge(){
        return $this->belongsTo(ChargeTable::class, 'chargeID', 'chargeID');
    }

    public function guest(){
        return $this->belongsTo(GuestTable::class, 'guestID', 'guestID');
    }
}
