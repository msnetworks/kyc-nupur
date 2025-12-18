<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStatus extends Model
{
    protected $table = 'case_status';
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}
