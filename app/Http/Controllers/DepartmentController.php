<?php

namespace App\Http\Controllers;

use App\DataTables\DepartmentsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
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
            'short_name' => 'required|max:10',
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
            'short_name' => [
                'required' => 'Nama singkat harus diisi',
                'max' => 'Nama singkat maksimal 10 karakter',
            ],
        ];

        $request->validate($rules, $message);

        try {
            if ($request->hasFile('logo')) {
                $currentDate = date('Y-m-d-H-i-s');
                $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
                $logo = $request->logo->storeAs('cabinets/departments/logo', $filename, 'public');
            }

            Department::create([
                'name' => $request->name,
                'description' => $request->description,
                'logo' => $logo,
                'short_name' => $request->short_name,
                'cabinet_id' => $request->cabinet,
            ]);

            return response()->json([
                'message' => 'Departemen ' . $request->name . ' berhasil ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Departemen ' . $request->name . ' gagal ditambahkan',
            ], 500);
        }
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
            'short_name' => 'required|max:10',
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
            'short_name' => [
                'required' => 'Nama singkat harus diisi',
                'max' => 'Nama singkat maksimal 10 karakter',
            ],
        ];

        $request->validate($rules, $message);

        try {
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
                'short_name' => $request->short_name,
            ]);

            return response()->json([
                'message' => 'Departemen ' . $request->name . ' berhasil diubah',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Departemen ' . $request->name . ' gagal diubah',
            ], 500);
        }
    }

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $count = 0;

        try {
            foreach ($ids as $id) {
                $department = Department::find($id);

                $programsCount = Program::where('department_id', $id)->count();
                $usersCount = User::where('department_id', $id)->count();

                if ($programsCount > 0 || $usersCount > 0) {
                    continue;
                }

                if ($department->logo) {
                    Storage::disk('public')->delete($department->logo);
                }

                $department->delete();
                $count++;
            }

            if ($count > 0) {
                $message = 'Berhasil menghapus ' . $count . ' departemen';

                if ($count != count($ids)) {
                    $message = 'Berhasil menghapus ' . $count . ' departemen dari ' . count($ids) . ' 
                    departemen yang dipilih, karena masih ada departemen yang memiliki program atau user';
                }

                return response()->json([
                    'message' => $message,
                ], 200);
            }

            return response()->json([
                'message' => 'Tidak ada departemen yang berhasil dihapus 
                karena masih ada departemen yang memiliki program atau user',
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Departemen gagal dihapus',
            ], 500);
        }
    }
}
