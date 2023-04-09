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

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role->name == 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Role superadmin tidak dapat dihapus',
            ]);
        }

        $user = User::role($role->name);

        if ($user->count() > 0) {
            return response()->json([
                'message' => 'Role tidak dapat dihapus karena masih digunakan',
            ], 422);
        } else {
            $role->delete();
            return response()->json([
                'message' => 'Role berhasil dihapus',
            ], 200);
        }
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
