<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        /**
         * Super Administrator
         */
        $superAdminDefaultPerms = [];

        // create super administrator default permissions
        foreach ($superAdminDefaultPerms as $superAdminDefaultPerm) {
            Permission::create(['name' => $superAdminDefaultPerm]);
        }

        /**
         * Administrator
         */
        $adminDefaultPerms = [];

        // create administrator default permissions
        foreach ($adminDefaultPerms as $adminDefaultPerm) {
            Permission::create(['name' => $adminDefaultPerm]);
        }

        /**
         * Customer
         */
        $customerDefaultPerms = [];

        // create manager default permissions
        foreach ($customerDefaultPerms as $customerDefaultPerm) {
            Permission::create(['name' => $customerDefaultPerm]);
        }

        $roles = [
            'super administrator' => $superAdminDefaultPerms,
            'administrator' => $adminDefaultPerms,
            'customer' => $customerDefaultPerms,
        ];

        $roleNames = array_keys($roles);
        foreach ($roles as $role => $permissions) {
            $roleIndex = array_search($role, $roleNames);
            for ($i = $roleIndex + 1; $i < count($roles); $i++) {
                $permissions = array_merge($permissions, $roles[$roleNames[$i]]);
            }
            Role::create([
                'name' => $role,
                'guard_name' => 'api'
            ])
                ->givePermissionTo($permissions);
        }
    }
}
