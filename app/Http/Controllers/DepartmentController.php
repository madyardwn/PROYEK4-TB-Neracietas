<?php

namespace App\Http\Controllers;

use App\DataTables\DepartmentsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use Illuminate\Http\Request;

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
            $originalName = $request->file('logo')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            $cabinet = Cabinet::find($request->cabinet);
            $logo = $request->file('logo')->storeAs($cabinet->year . '-' . $cabinet->name . '/' . $request->name . '/logo', $filename, 'public');
        }

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logo,
            'cabinet_id' => $request->cabinet,
        ]);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan');
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
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('logo')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            // delete old logo
            if ($department->logo) {
                unlink(public_path('storage/' . $department->logo));
            }

            $cabinet = Cabinet::find($request->cabinet);

            $logo = $request->file('logo')->storeAs($cabinet->year . '-' . $cabinet->name . '/' . $request->name . '/logo', $filename, 'public');
        }

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logo ?? $department->logo,
            'cabinet_id' => $request->cabinet,
        ]);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diubah');
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        // delete logo
        if ($department->logo) {
            unlink(public_path('storage/' . $department->logo));
        }

        // delete users
        foreach ($department->users as $user) {
            $user->delete();
        }

        // delete events
        foreach ($department->events as $event) {
            $event->delete();
        }

        // delete departments
        $department->delete();
    }
}
