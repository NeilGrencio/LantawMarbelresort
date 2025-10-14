<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userID';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'status',
        'fcm_token'
    ];

    // Manual notifications relationship to handle custom primary key
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    public function routeNotificationFor($channel)
    {
        if ($channel === 'database') {
            return $this->getKey();
        }

        if ($channel === 'fcm') {
            return $this->fcm_token;
        }

        return null;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }
    
    public function guest()
    {
        return $this->hasOne(GuestTable::class, 'userID','userID');
    }

    public function staff()
    {
        return $this->hasOne(StaffTable::class, 'userID');
    }
}
