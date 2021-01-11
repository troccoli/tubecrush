<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'register users']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'update posts']);
        Permission::create(['name' => 'delete posts']);

        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Editor'])
            ->givePermissionTo(['create posts', 'view posts', 'update posts']);
    }
}
