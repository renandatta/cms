<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $fillable = [
        'nama', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function last_login()
    {
        return $this->hasOne(UserAuth::class, 'user_id', 'id')
            ->orderBy('id', 'desc');
    }
}
