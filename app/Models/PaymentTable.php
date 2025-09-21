<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTable extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'paymentID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'totaltender', 'totalchange', 'datepayment', 'guestID', 'billingID','refNumber'
    ];

    public function billing()
    {
        return $this->belongsTo(BillingTable::class, 'billingID', 'billingID');
    }
}
