<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadCard extends Model
{
    protected $fillable = [
        'use_type',
        'employee_code',
        'name',
        'address',
        'mobile_no',
        'aadhaar_card',
        'photo',
        'agent_id',
        'status',
        'created_by',
        'updated_by',
    ];

    public function agent()
    {
        return $this->belongsTo('App\Models\User', 'agent_id', 'id');
    }

    public function masterStatus()
    {
        return $this->belongsTo('App\Models\CaseStatus', 'status', 'id');
    }

    public function commonFlow()
    {
        return $this->hasOne('App\Models\UpdateCardCommonFlow', 'upload_card_id', 'id');
    }
}
