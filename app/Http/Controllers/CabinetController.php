<?php

namespace App\Http\Controllers;

use App\DataTables\CabinetsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CabinetController extends Controller
{
    public function index(CabinetsDataTable $dataTable)
    {
        return $dataTable->render('pages.cabinets.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'year' => 'required|numeric',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'year' => [
                'required' => 'Tahun harus diisi',
                'numeric' => 'Tahun harus berupa angka',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'logo' => [
                'required' => 'Logo harus diisi',
                'image' => 'Logo harus berupa gambar',
                'mimes' => 'Logo harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Logo maksimal 2 MB',
            ],
            'is_active' => [
                'required' => 'Status harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('logo')) {
            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
            $logo = $request->logo->storeAs('cabinets/logo', $filename, 'public');
        }

        Cabinet::create([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'logo' => $logo ?? null,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'message' => 'Kabinet ' . $request->name . ' berhasil ditambahkan',
        ], 200);
    }

    public function edit(Cabinet $cabinet): Cabinet
    {
        return $cabinet;
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'year' => 'required|numeric',
            'description' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'year' => [
                'required' => 'Tahun harus diisi',
                'numeric' => 'Tahun harus berupa angka',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
                'max' => 'Deskripsi maksimal 255 karakter',
            ],
            'logo' => [
                'image' => 'Logo harus berupa gambar',
                'mimes' => 'Logo harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Logo maksimal 2 MB',
            ],
            'is_active' => [
                'required' => 'Status harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $cabinet = Cabinet::find($id);

        if ($request->hasFile('logo')) {
            if ($cabinet->logo) {
                Storage::disk('public')->delete($cabinet->logo);
            }

            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
            $logo = $request->logo->storeAs('cabinets/logo', $filename, 'public');
        }

        $cabinet->update([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'logo' => $logo ?? $cabinet->logo,
            'is_active' => $request->is_active,
        ]);

        $departments = Department::where('cabinet_id', $cabinet->id);

        if ($departments->count() > 0) {
            // Update user active status if department exists
            $users = User::whereIn('department_id', $departments->pluck('id'))->get();

            if ($users->count() > 0) {
                foreach ($users as $user) {
                    $user->update([
                        'is_active' => $request->is_active,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Kabinet ' . $request->name . ' berhasil diperbarui',
        ], 200);
    }

    public function destroy($id)
    {
        $cabinet = Cabinet::find($id);

        $departments = Department::where('cabinet_id', $cabinet->id);

        if ($departments->count() > 0) {

            return response()->json([
                'message' => 'Kabinet tidak dapat dihapus karena masih memiliki departemen',
            ], 422);
        } else {
            if ($cabinet->logo) {
                Storage::disk('public')->delete($cabinet->logo);
            }

            $cabinet->delete();

            return response()->json([
                'message' => 'Kabinet ' . $cabinet->name . ' berhasil dihapus',
            ], 200);
        }
    }
}
