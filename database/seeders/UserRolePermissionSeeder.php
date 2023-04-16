<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultUser = [
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];

        $admin = User::create(array_merge([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ], $defaultUser));

        $role_superadmin = Role::create(['name' => 'superadmin']);

        $permission = [
            'read',
            'create',
            'update',
            'delete',
        ];

        $models = [
            'user',
            'role',
            'permission',
            'cabinet',
            'department',
            'event',
            'program',
        ];

        foreach ($models as $model) {
            foreach ($permission as $perm) {
                Permission::create(['name' => "{$perm} {$model}"]);
            }
        }

        $role_superadmin->givePermissionTo(Permission::all());

        $admin->assignRole($role_superadmin);
    }
}
