<?php

namespace App\Http\Controllers;

use App\DataTables\CabinetsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
            $originalName = $request->file('logo')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            $logo = $request->file('logo')->storeAs($request->year . '-' . $request->name . '/' .  '/logo', $filename, 'public');
        }

        $cabinet = Cabinet::create([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'logo' => $logo,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('cabinets.index')->with('success', 'Kabinet berhasil ditambahkan');
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
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('logo')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            $logo = $request->file('logo')->storeAs($request->year . '-' . $request->name .  '/logo', $filename, 'public');
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

        return redirect()->route('cabinets.index')->with('success', 'Kabinet berhasil diubah');
    }

    public function destroy($id)
    {
        $cabinet = Cabinet::findOrFail($id);
        // Lakukan pengecekan terhadap kabinet
        if (!$cabinet) {
            return redirect()->route('cabinets.index')->with('error', 'Kabinet tidak ditemukan');
        }

        // Hapus logo kabinet
        $logoPath = public_path('storage/' . $cabinet->logo);
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }

        // Hapus departemen dan pengguna yang terkait
        foreach ($cabinet->departments as $department) {
            // Hapus logo departemen
            $logoPath = public_path('storage/' . $department->logo);
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }

            // Hapus pengguna dan peran yang terkait
            foreach ($department->users as $user) {
                // Hapus avatar pengguna
                $avatarPath = public_path('storage/' . $user->avatar);
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }

                // Hapus peran pengguna
                $user->roles()->delete();

                // Hapus pengguna
                $user->delete();
            }

            // Hapus departemen
            $department->delete();
        }

        // Hapus kabinet
        $cabinet->delete();

        return redirect()->route('cabinets.index')->with('success', 'Kabinet berhasil dihapus');
    }
}
