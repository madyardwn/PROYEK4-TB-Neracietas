<?php

namespace App\Http\Controllers;

use App\DataTables\CabinetsDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Periode;
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

                if ($cabinet->logo) {
                    Storage::disk('public')->delete($cabinet->logo);
                }

                if ($cabinet->filosofy) {
                    Storage::disk('public')->delete($cabinet->filosofy);
                }

                $cabinet->delete();
                $count++;
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
