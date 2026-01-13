<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function logActivity($action, $description = null)
    {
        if(isset(Auth::guard('admin')->user()->id)){
           $user_id =  Auth::guard('admin')->user()->id;
        }else if(Auth::id()){
            $user_id = Auth::id();
        }else{
            $user_id = null;
        }

        ActivityLog::create([
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
