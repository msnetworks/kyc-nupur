<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Bank extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'banks';
    /**
     * Set the default guard for this model.
     *
     * @var string
     */
    protected $guard_name = 'admin';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bank_product_mappings', 'bank_id', 'product_id');
    }
}
