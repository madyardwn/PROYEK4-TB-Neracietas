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



        // Create Permissions
        $permission = [
            'read',
            'create',
            'update',
            'delete',
        ];

        $models = [
            'user',
            'permission',
            'role',
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

        // permission for login in mobile and web
        Permission::create(['name' => 'login mobile']);
        Permission::create(['name' => 'login web']);

        // Create Roles
        Role::create(['name' => 'superadmin'])->givePermissionTo(Permission::all());

        Role::create(['name' => 'ketua himpunan'])->givePermissionTo(
            [
                'login mobile',
                'login web',
                'read user',

                'read cabinet',
                'create cabinet',
                'update cabinet',
                'delete cabinet',

                'read department',
                'create department',
                'update department',
                'delete department',
            ]
        );
        Role::create(['name' => 'wakil ketua himpunan'])->givePermissionTo(
            [
                'login mobile',
                'login web',
                'read user',

                'read cabinet',
                'create cabinet',
                'update cabinet',
                'delete cabinet',

                'read department',
                'create department',
                'update department',
                'delete department',
            ]
        );

        Role::create(['name' => 'ketua divisi'])->givePermissionTo(
            [
                'login mobile',
                'login web',

                'read program',
                'create program',
                'update program',
                'delete program',

                'read department',
                'create department',
                'update department',
            ]
        );
        Role::create(['name' => 'wakil ketua divisi'])->givePermissionTo(
            [
                'login mobile',
                'login web',

                'read program',
                'create program',
                'update program',
                'delete program',

                'read department',
                'create department',
                'update department',
            ]
        );

        Role::create(['name' => 'ketua majelis perwakilan anggota'])->givePermissionTo(
            [
                'login mobile',
            ]
        );
        Role::create(['name' => 'wakil ketua majelis perwakilan anggota'])->givePermissionTo(
            [
                'login mobile',
            ]
        );

        Role::create(['name' => 'staf ahli'])->givePermissionTo(
            [
                'login mobile',
                'login web',

                'read program',
                'create program',
                'update program',
                'delete program',
            ]
        );
        Role::create(['name' => 'staf muda'])->givePermissionTo(
            [
                'login mobile',
                'login web',

                'read program',
                'create program',
                'update program',
                'delete program',
            ]
        );


        // Create Users
        User::create(
            array_merge(
                [
                    'name' => 'superadmin',
                    'email' => 'admin@gmail.com',
                ], $defaultUser
            )
        )->assignRole('superadmin');
    }
}
