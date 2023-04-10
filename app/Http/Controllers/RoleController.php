<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('pages.roles.index', [
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:20',
            'permissions' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 20 karakter',
            ],
            'permissions' => [
                'required' => 'Permission harus dipilih',
            ],
        ];

        $request->validate($rules, $message);

        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Role
    {
        return $role
            ->load('permissions');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|max:50',
            'permissions' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'permissions' => [
                'required' => 'Permission harus dipilih',
            ],
        ];

        $request->validate($rules, $message);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui',
        ]);
    }

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $count = 0;

        foreach ($ids as $id) {
            $role = Role::find($id);

            $usersCount = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })->count();

            if ($usersCount > 0) {
                continue;
            }

            $role->permissions()->detach();
            $role->delete();
            $count++;
        }

        if ($count > 0) {
            $message = 'Berhasil menghapus ' . $count . ' role';

            if ($count != count($ids)) {
                $message = 'Berhasil menghapus ' . $count . ' role dari ' . count($ids) . ' 
                role yang dipilih, karena masih ada role yang dimiliki user';
            }

            return response()->json([
                'message' => $message,
            ], 200);
        }

        return response()->json([
            'message' => 'Tidak ada role yang berhasil dihapus 
            karena masih ada role yang dimiliki user',
        ], 403);
    }

    public function assignPermission(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Permission berhasil ditambahkan',
        ], 200);
    }

    public function removePermission(Request $request, Role $role)
    {
        $role->revokePermissionTo($request->permissions);

        return response()->json([
            'message' => 'Permission berhasil dihapus',
        ], 200);
    }
}
