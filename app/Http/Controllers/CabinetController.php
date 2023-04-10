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

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kabinet ' . $request->name . ' gagal ditambahkan',
            ], 500);
        }
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
                'name' => 'Departemen Luar Himpunan',
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

        try {
            foreach ($departments as $department) {
                Department::create([
                    'name' => $department['name'],
                    // 'description' => $department['description'],
                    'cabinet_id' => $cabinet->id,
                ]);
            }

            return response()->json([
                'message' => 'Departemen berhasil dibuat',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Departemen gagal dibuat',
            ], 500);
        }
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

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kabinet gagal diperbarui',
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
                $cabinet = Cabinet::find($id);

                $departmentsCount = Department::where('cabinet_id', $id)->count();

                if ($departmentsCount > 0) {
                    continue;
                }

                if ($cabinet->logo) {
                    Storage::disk('public')->delete($cabinet->logo);
                }

                $cabinet->delete();
                $count++;
            }

            if ($count > 0) {
                $message = 'Berhasil menghapus ' . $count . ' kabinet';

                if ($count != count($ids)) {
                    $message = 'Berhasil menghapus ' . $count . ' kabinet dari ' . count($ids) . ' 
                    kabinet yang dipilih, karena masih ada kabinet yang memiliki departemen';
                }

                return response()->json([
                    'message' => $message,
                ], 200);
            }

            return response()->json([
                'message' => 'Tidak ada kabinet yang berhasil dihapus 
                karena masih ada kabinet yang memiliki departemen',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kabinet gagal dihapus',
            ], 500);
        }
    }
}
