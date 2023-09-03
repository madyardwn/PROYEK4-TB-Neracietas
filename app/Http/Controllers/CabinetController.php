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
            'filosofy' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visi' => 'nullable',
            'misi' => 'nullable',
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
            'filosofy' => [
                'image' => 'Filosofy harus berupa gambar',
                'mimes' => 'Filosofy harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Filosofy maksimal 2 MB',
            ],
            'visi' => [
                'nullable' => 'Visi harus berupa teks',
            ],
            'misi' => [
                'nullable' => 'Misi harus berupa teks',
            ],
        ];

        $request->validate($rules, $message);

        try {
            if ($request->hasFile('logo')) {
                $currentDate = date('Y-m-d-H-i-s');
                $filename = $currentDate . '_' . $request->name . '.' . $request->logo->extension();
                $logo = $request->logo->storeAs('cabinets/logo', $filename, 'public');
            }

            if ($request->hasFile('filosofy')) {
                $currentDate = date('Y-m-d-H-i-s');
                $filename = $currentDate . '_' . $request->name . '.' . $request->filosofy->extension();
                $filosofy = $request->filosofy->storeAs('cabinets/filosofy', $filename, 'public');
            }

            $cabinet = Cabinet::create(
                [
                    'name' => $request->name,
                    'year' => $request->year,
                    'description' => $request->description,
                    'logo' => $logo,
                    'is_active' => $request->is_active ?? 0,
                    'filosofy' => $filosofy ?? null,
                    'visi' => $request->visi ?? null,
                    'misi' => $request->misi ?? null,
                ]
            );

            $this->generateDepartments($cabinet);

            return response()->json(
                [
                    'message' => 'Kabinet ' . $request->name . ' berhasil ditambahkan',
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Kabinet ' . $request->name . ' gagal ditambahkan',
                ],
                500
            );
        }
    }

    public function generateDepartments(Cabinet $cabinet)
    {
        $departments = [
            [
                'name' => 'Biro Administrasi & Sekretariat',
                'short_name' => 'Adkes',
                'logo' => '',
                'description' => 'Biro Administrasi & Sekretariat bertanggung jawab atas segala urusan administrasi dan keuangan Himpunan Mahasiswa Teknik Komputer Polban. Selain itu, Biro Administrasi & Sekretariat juga bertanggung jawab atas segala urusan keuangan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Biro Keuangan',
                'short_name' => 'Keuangan',
                'logo' => '',
                'description' => 'Biro Keuangan bertanggung jawab atas segala urusan keuangan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Biro Kewirausahaan',
                'short_name' => 'Kewirausahaan',
                'logo' => '',
                'description' => 'Biro Kewirausahaan bertanggung jawab atas segala urusan kewirausahaan Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Luar Himpunan',
                'short_name' => 'Luhim',
                'logo' => '',
                'description' => 'Biro Luar Himpunan bertanggung jawab atas segala urusan luar Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Riset, Pendidikan, dan Teknologi',
                'short_name' => 'Risetdikti',
                'logo' => '',
                'description' => 'Departemen Riset, Pendidikan, dan Teknologi bertanggung jawab atas segala urusan riset, pendidikan, dan teknologi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Pengembangan Sumber Daya Anggota',
                'short_name' => 'PSDA',
                'logo' => '',
                'description' => 'Departemen Pengembangan Sumber Daya Anggota bertanggung jawab atas segala urusan pengembangan sumber daya anggota Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Komunikasi & Informasi',
                'short_name' => 'Kominfo',
                'logo' => '',
                'description' => 'Departemen Komunikasi & Informasi bertanggung jawab atas segala urusan komunikasi dan informasi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Unit Teknologi',
                'short_name' => 'Tekno',
                'logo' => '',
                'description' => 'Unit Teknologi bertanggung jawab atas segala urusan teknologi Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Departemen Seni & Olahraga',
                'short_name' => 'Senor',
                'logo' => '',
                'description' => 'Departemen Seni & Olahraga bertanggung jawab atas segala urusan seni dan olahraga Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Majelis Perwakilan Anggota',
                'short_name' => 'MPA',
                'logo' => '',
                'description' => 'Majelis Perwakilan Anggota bertanggung jawab atas segala urusan perwakilan anggota Himpunan Mahasiswa Teknik Komputer Polban.',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Komputer Polban',
                'short_name' => 'HIMAKOM',
                'logo' => '',
                'description' => 'Himpunan Mahasiswa Teknik Komputer Polban bertanggung jawab atas segala urusan Himpunan Mahasiswa Teknik Komputer Polban.',
            ]
        ];

        try {
            foreach ($departments as $department) {
                Department::create(
                    [
                        'name' => $department['name'],
                        'short_name' => $department['short_name'],
                        // 'description' => $department['description'],
                        'cabinet_id' => $cabinet->id,
                    ]
                );
            }

            return response()->json(
                [
                    'message' => 'Departemen berhasil dibuat',
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Departemen gagal dibuat',
                ],
                500
            );
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
            'filosofy' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visi' => 'nullable',
            'misi' => 'nullable',
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
            'filosofy' => [
                'image' => 'Filosofy harus berupa gambar',
                'mimes' => 'Filosofy harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Filosofy maksimal 2 MB',
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

            if ($request->hasFile('filosofy')) {
                if ($cabinet->filosofy) {
                    Storage::disk('public')->delete($cabinet->filosofy);
                }

                $currentDate = date('Y-m-d-H-i-s');
                $filename = $currentDate . '_' . $request->name . '.' . $request->filosofy->extension();
                $filosofy = $request->filosofy->storeAs('cabinets/filosofy', $filename, 'public');
            }

            $cabinet->update(
                [
                    'name' => $request->name,
                    'year' => $request->year,
                    'description' => $request->description,
                    'logo' => $logo ?? $cabinet->logo,
                    'is_active' => $request->is_active ?? 0,
                    'filosofy' => $filosofy ?? $cabinet->filosofy,
                    'visi' => $request->visi ?? '',
                    'misi' => $request->misi ?? '',
                ]
            );

            $departments = Department::where('cabinet_id', $cabinet->id);

            if ($departments->count() > 0) {
                // Update user active status if department exists
                foreach ($departments->get() as $department) {
                    $department->users()->update(['is_active' => $request->is_active ?? 0]);
                }
            }

            return response()->json(
                [
                    'message' => 'Kabinet ' . $request->name . ' berhasil diperbarui',
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Kabinet gagal diperbarui, ' . $e->getMessage(),
                ],
                500
            );
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

                return response()->json(
                    [
                        'message' => $message,
                    ],
                    200
                );
            }

            return response()->json(
                [
                    'message' => 'Tidak ada kabinet yang berhasil dihapus
                karena masih ada kabinet yang memiliki departemen',
                ],
                403
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Kabinet gagal dihapus',
                ],
                500
            );
        }
    }
}
