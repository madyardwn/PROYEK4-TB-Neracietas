<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('pages.roles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
        ];

        $request->validate($rules, $message);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Role
    {
        return $role;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|max:50',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
        ];

        $request->validate($rules, $message);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
    }

    public function assignPermission(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Permission berhasil ditambahkan');
    }

    public function removePermission(Request $request, Role $role)
    {
        $role->revokePermissionTo($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Permission berhasil dihapus');
    }
}
