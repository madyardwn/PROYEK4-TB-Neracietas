<?php

namespace App\Http\Controllers;

use App\DataTables\DepartmentsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    public function index(DepartmentsDataTable $dataTable)
    {
        return $dataTable->render('pages.departments.index', [
            'cabinets' => Cabinet::all(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cabinet' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
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
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('logo')) {
            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
            $logo = $request->logo->storeAs('cabinets/departments/logo', $filename, 'public');
        }

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logo,
            'cabinet_id' => $request->cabinet,
        ]);

        return response()->json([
            'message' => 'Departemen ' . $request->name . ' berhasil ditambahkan',
        ], 200);
    }

    public function edit(Department $department): Department
    {
        return $department;
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'cabinet' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
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
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $department = Department::find($id);

        if ($request->hasFile('logo')) {
            // delete old logo
            if ($department->logo) {
                Storage::disk('public')->delete($department->logo);
            }

            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
            $logo = $request->logo->storeAs('cabinets/departments/logo', $filename, 'public');
        }

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logo ?? $department->logo,
            'cabinet_id' => $request->cabinet,
        ]);

        return response()->json([
            'message' => 'Departemen ' . $request->name . ' berhasil diubah',
        ], 200);
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        $programs = Program::where('department_id', $id)->get();

        if ($department->users->count() > 0) {
            return response()->json([
                'message' => 'Departemen tidak dapat dihapus karena masih memiliki anggota',
            ], 400);
        } else if ($programs->count() > 0) {
            return response()->json([
                'message' => 'Departemen tidak dapat dihapus karena masih memiliki program',
            ], 400);
        } else {
            // delete logo
            if ($department->logo) {
                Storage::disk('public')->delete($department->logo);
            }

            $department->delete();

            return response()->json([
                'message' => 'Departemen ' . $department->name . ' berhasil dihapus',
            ], 200);
        }
    }
}
