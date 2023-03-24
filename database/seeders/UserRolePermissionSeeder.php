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
            'name' => '',
            'email' => 'admin@example.com',
        ], $defaultUser));

        $user = User::create(array_merge([
            'name' => 'madya',
            'email' => 'madya@example.com',
        ], $defaultUser));

        $role_admin = Role::create(['name' => 'admin']);
        $role_user = Role::create(['name' => 'user']);

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
        ];

        foreach ($models as $model) {
            foreach ($permission as $perm) {
                Permission::create(['name' => "{$perm} {$model}"]);
            }
        }

        $role_admin->givePermissionTo(Permission::all());
        $role_user->givePermissionTo([
            'read user',
            'read role',
            'read permission',
        ]);

        $admin->assignRole($role_admin);
        $user->assignRole($role_user);
    }
}
