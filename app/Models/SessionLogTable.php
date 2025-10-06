<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionLogTable extends Model
{
    protected $table = 'usersessionlog';
    protected $primaryKey = 'sessionID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'activity', 'date', 'userID'
    ];

    public function user()
{
    return $this->belongsTo(UserTable::class, 'userID', 'userID');
}
}
