<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Add this
use Illuminate\Notifications\Notifiable;

class Business extends Authenticatable // Extend Authenticatable
{
    use Notifiable;

    protected $guard = 'business'; // Keep this line

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
