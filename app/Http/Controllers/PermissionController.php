<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionsDataTable;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('pages.permissions.index');
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

        Permission::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Permission berhasil ditambahkan',
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return $permission;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
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

        $permission->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Permission berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        // jika permission masih digunakan oleh role maka tidak bisa dihapus
        if ($permission->roles->count() > 0) {
            return response()->json([
                'message' => 'Permission ' . $permission->name . ' masih digunakan oleh role',
            ], 422);
        } else {
            $permission->delete();

            return response()->json([
                'message' => 'Permission berhasil dihapus',
            ], 200);
        }
    }
}
