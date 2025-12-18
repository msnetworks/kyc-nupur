<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseHistory extends Model
{
    protected $table = 'case_history';
    protected $fillable = [
        'case_id',
        'user_id',
        'status',
        'sub_status',
        'assign_to',
        'remark',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function getCreatedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getCaseSubStatus()
    {
        return $this->hasOne('App\Models\CaseStatus', 'id', 'sub_status');
    }

    public function getAgent()
    {
        return $this->belongsTo('App\Models\User', 'assign_to', 'id');
    }

    public function getCase()
    {
        return $this->belongsTo('App\Models\Cases', 'case_id', 'id');
    }

}


