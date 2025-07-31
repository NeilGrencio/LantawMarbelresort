<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'staffID';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'firstname', 'lastname', 'mobilenum', 'email', 'gender', 'role', 'avatar', 'userID' 
    ];

    public function Users(){
        return $this->belongsTo(UserTable::class, 'userID');
    }
}
