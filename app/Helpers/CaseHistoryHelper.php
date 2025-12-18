<?php
namespace App\Helpers;

use App\Models\CaseHistory;
use Illuminate\Support\Facades\Auth;

class CaseHistoryHelper
{
    public static function logHistory($caseId=null, $status=0,$sub_status=0,$assign_to=null, $remark= 'New Case',$action=null,$description = null)
    {
        if(isset(Auth::guard('admin')->user()->id)){
            $user_id =  Auth::guard('admin')->user()->id;
        }else if(Auth::id()){
            $user_id = Auth::id();
        }else{
            $user_id = null;
        }

        CaseHistory::create([
            'case_id' => $caseId,
            'user_id' => $user_id,
            'status'  => $status,
            'sub_status' => $sub_status,
            'assign_to' => $assign_to,
            'remark'    => $remark,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
