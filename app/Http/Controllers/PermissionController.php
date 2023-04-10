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

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $count = 0;

        foreach ($ids as $id) {
            $permission = Permission::find($id);

            $rolesCount = $permission->roles()->count();

            if ($rolesCount > 0) {
                continue;
            }

            $permission->delete();
            $count++;
        }

        if ($count > 0) {
            $message = 'Berhasil menghapus ' . $count . ' permission';

            if ($count != count($ids)) {
                $message = 'Berhasil menghapus ' . $count . ' permission dari ' . count($ids) . ' 
                permission yang dipilih, karena masih ada permission yang digunakan role';
            }

            return response()->json([
                'message' => $message,
            ], 200);
        }

        return response()->json([
            'message' => 'Tidak ada permission yang berhasil dihapus 
            karena masih ada permission yang digunakan role',
        ], 403);
    }
}
