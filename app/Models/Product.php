<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Product extends Authenticatable
{
    use Notifiable, HasRoles;
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

    public function getProductsByBankId($bankId)
    {
        // Fetch products from the database based on 'bankId'
        $query = $this->db->get_where('products', ['bank_id' => $bankId]);
        return $query->result();
    }
}
