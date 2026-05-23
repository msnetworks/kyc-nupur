<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
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
        'name', 'email', 'mobile', 'password', 'parent_id', 'assigned_users', 'is_blocked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public function verifiedCases()
    {
        return $this->hasMany('App\Models\casesFiType', 'verifiers_name', 'id');
    }

    /**
     * Get the default agent assigned to this admin
     */
    public function defaultAgent()
    {
        return $this->belongsTo('App\Models\User', 'default_agent_assign', 'id');
    }

    /**
     * Get the parent admin who created this admin
     */
    public function parentAdmin()
    {
        return $this->belongsTo('App\Models\Admin', 'parent_id', 'id');
    }

    /**
     * Get all assigned users for this admin
     */
    public function assignedUsers()
    {
        try {
            if (!$this->assigned_users || empty(trim($this->assigned_users))) {
                return collect();
            }
            
            $userIds = explode(',', trim($this->assigned_users));
            $userIds = array_filter(array_map('intval', $userIds));
            
            if (empty($userIds)) {
                return collect();
            }
            
            return \App\User::whereIn('id', $userIds)->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching assigned users: ' . $e->getMessage());
            return collect();
        }
    }
}

