<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ApplicationType extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'status',
    ];

    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
}
