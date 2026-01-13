<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BranchCode extends Model
{
    use Notifiable, HasRoles;

    // Define the table associated with this model
    protected $table = 'branch_code';

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
        'bank_id', 'branch_code', 'branch_name'
    ];

    /**
     * Define the relationship with the Bank table.
     * This assumes a `banks` table exists where `id` is the primary key.
     *
     * Each branch belongs to one bank.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
