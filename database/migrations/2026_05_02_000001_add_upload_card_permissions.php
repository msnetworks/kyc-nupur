<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUploadCardPermissions extends Migration
{
    private $permissions = [
        'upload_card.create',
        'upload_card.view',
        'upload_card.edit',
        'upload_card.delete',
    ];

    public function up()
    {
        foreach ($this->permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'admin'],
                [
                    'group_name' => 'upload_card',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $superAdminRole = DB::table('roles')
            ->where('name', 'superadmin')
            ->where('guard_name', 'admin')
            ->first();

        if ($superAdminRole) {
            $permissionIds = DB::table('permissions')
                ->whereIn('name', $this->permissions)
                ->where('guard_name', 'admin')
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $superAdminRole->id,
                ]);
            }
        }

        app('cache')->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down()
    {
        $permissionIds = DB::table('permissions')
            ->whereIn('name', $this->permissions)
            ->where('guard_name', 'admin')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();

        app('cache')->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
}
