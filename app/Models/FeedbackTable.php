<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackTable extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'feedbackID';
    public $timestamps = false;
    protected $fillable = [
        'message',
        'date',
        'rating',
        'status',
        'guestID'
    ];

    public function guest()
    {
        return $this->belongsTo(GuestTable::class, 'guestID', 'guestID');
    }
}
