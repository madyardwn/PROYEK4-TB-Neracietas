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

        $cabinet = Cabinet::create([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'logo' => $logo,
            'is_active' => $request->is_active,
        ]);

        $this->generateDepartments($cabinet);

        return response()->json([
            'message' => 'Kabinet ' . $request->name . ' berhasil ditambahkan',
        ], 200);
    }

    public function generateDepartments(Cabinet $cabinet)
    {
        $departments = [
            [
                'name' => 'Biro Administrasi & Sekretariat',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Biro Administrasi & Sekretariat.png',
                'description' => 'Biro Administrasi & Sekretariat bertanggung jawab atas segala urusan administrasi dan keuangan Himpunan Mahasiswa Teknik Komputer Polban. Selain itu, Biro Administrasi & Sekretariat juga bertanggung jawab atas segala urusan keuangan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Biro Keuangan',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Biro Keuangan.png',
                'description' => 'Biro Keuangan bertanggung jawab atas segala urusan keuangan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Biro Kewirausahaan',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Biro Kewirausahaan.png',
                'description' => 'Biro Kewirausahaan bertanggung jawab atas segala urusan kewirausahaan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Biro Luar Himpunan',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Biro Luar Himpunan.png',
                'description' => 'Biro Luar Himpunan bertanggung jawab atas segala urusan luar Himpunan Mahasiswa Teknik Komputer Polban.',
            ],

            [
                'name' => 'Departemen Riset, Pendidikan, dan Teknologi',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Departemen Riset, Pendidikan, dan Teknologi.png',
                'description' => 'Departemen Riset, Pendidikan, dan Teknologi bertanggung jawab atas segala urusan riset, pendidikan, dan teknologi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Pengembangan Sumber Daya Anggota',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Departemen Pengembangan Sumber Daya Anggota.png',
                'description' => 'Departemen Pengembangan Sumber Daya Anggota bertanggung jawab atas segala urusan pengembangan sumber daya anggota Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Komunikasi & Informasi',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Departemen Komunikasi & Informasi.png',
                'description' => 'Departemen Komunikasi & Informasi bertanggung jawab atas segala urusan komunikasi dan informasi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],

            [
                'name' => 'Unit Teknologi',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Unit Teknologi.png',
                'description' => 'Unit Teknologi bertanggung jawab atas segala urusan teknologi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Seni & Olahraga',
                'logo' => 'cabinets/logo/2021-06-01-10-00-00_Departemen Seni & Olahraga.png',
                'description' => 'Departemen Seni & Olahraga bertanggung jawab atas segala urusan seni dan olahraga Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department['name'],
                'cabinet_id' => $cabinet->id,
            ]);
        }

        return response()->json([
            'message' => 'Departemen berhasil dibuat',
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
