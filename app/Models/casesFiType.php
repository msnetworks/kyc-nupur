<?php

namespace App\Models;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class casesFiType extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'case_id', 'fi_type_id', 'mobile', 'user_id', 'address', 'pincode', 'land_mark',
    ];
    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function getUserVisitedBy()
    {
        return $this->belongsTo('App\Models\User', 'visited_by', 'id');
    }
    public function getUserVerifiedBy()
    {
        return $this->belongsTo('App\Models\Admin', 'verified_by', 'id');
    }
    public function getUserVerifiersName()
    {
        return $this->belongsTo('App\Models\Admin', 'verifiers_name', 'id');
    }

    public function getCase()
    {
        return $this->belongsTo('App\Models\Cases', 'case_id', 'id');
    }

    public function getCaseFiType() 
    {
        return $this->hasMany('App\Models\casesFiType', 'case_id');
    }

    // public function getFiType(){
    //     return $this->hasMany('App\Models\FiType','id','fi_type_id');
    // }

    public function getFiType()
    {
        return $this->hasOne('App\Models\FiType', 'id', 'fi_type_id');
    }
    public function getBranch()
    {
        return $this->hasOne('App\Models\BranchCode', 'id', 'branch_code');
    }
    public function getStatus()
    {
        return $this->hasOne('App\Models\CaseStatus', 'id', 'status');
    }
    public function getCaseStatus()
    {
        return $this->hasOne('App\Models\CaseStatus', 'id', 'sub_status');
    }
}
