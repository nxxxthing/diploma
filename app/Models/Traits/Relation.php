<?php

namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

trait Relation
{
    private $permissions = [
        'access',
        'create',
        'edit',
        'delete',
        'show'
    ];

    public function touchPermission($table, $role = 'system_administrator')
    {
        $role = Role::where('slug', $role)->first();

        $permission_roles = [];

        if ($role) {
            foreach ($this->permissions as $permission) {
                $permission_to_save = Permission::where('slug', $table . '_' . $permission)->first();

                if (!$permission_to_save) {
                    $permission_to_save = Permission::create([
                        'slug' => $table . '_' . $permission
                    ]);
                }

                $permission_roles[] = [
                    'role_id'       => $role->id,
                    'permission_id' => $permission_to_save->id
                ];
            }

            foreach ($permission_roles as $permission_role) {
                $exists = DB::table('permission_role')
                    ->where('role_id', $permission_role['role_id'])
                    ->where('permission_id', $permission_role['permission_id'])
                    ->exists();

                if (!$exists) {
                    DB::table('permission_role')->insert($permission_role);
                }
            }
        }
    }
}
