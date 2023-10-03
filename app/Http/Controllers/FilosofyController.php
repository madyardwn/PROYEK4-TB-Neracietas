<?php

namespace App\Http\Controllers;

use App\DataTables\FilosofyDataTable;
use App\Models\Cabinet;
use App\Models\Filosofy;
use Illuminate\Http\Request;

class FilosofyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilosofyDataTable $dataTable)
    {
        return $dataTable->render('pages.filosofy.index',[
            'cabinets' => Cabinet::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'label' => 'required|max:255',
            'cabinet' => 'required|exists:cabinets,id',            
        ];

        $message = [
            'logo' => [
                'required' => 'Logo harus diisi',
                'image' => 'Logo harus berupa gambar',
                'mimes' => 'Logo harus berupa gambar dengan format jpeg, png, jpg, gif, atau svg',
                'max' => 'Logo maksimal 2 MB',
            ],
            'label' => [
                'required' => 'Label harus diisi',
                'max' => 'Label maksimal 50 karakter',
            ],
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
                'exists' => 'Kabinet tidak ditemukan',
            ],
        ];

        $request->validate($rules, $message);

        $logo = $request->file('logo')->store('filosofy', 'public');

        Filosofy::create([
            'logo' => $logo,
            'label' => $request->label,
            'cabinet_id' => $request->cabinet,
        ]);

        return redirect()->route('filosofy.index')->with('success', 'Filosofy berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Filosofy $filosofy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filosofy $filosofy)
    {
        return $filosofy;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filosofy $filosofy)
    {
        $rules = [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'label' => 'required|max:255',
            'cabinet' => 'required|exists:cabinets,id',
        ];

        $message = [
            'logo' => [
                'image' => 'Logo harus berupa gambar',
                'mimes' => 'Logo harus berupa gambar dengan format jpeg, png, jpg, gif, atau svg',
                'max' => 'Logo maksimal 2 MB',
            ],
            'label' => [
                'required' => 'Label harus diisi',
                'max' => 'Label maksimal 50 karakter',
            ],
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
                'exists' => 'Kabinet tidak ditemukan',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('filosofy', 'public');
            $filosofy->logo = $logo;
        }

        $filosofy->label = $request->label;
        $filosofy->cabinet_id = $request->cabinet;
        $filosofy->save();

        return redirect()->route('filosofy.index')->with('success', 'Filosofy berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $count = 0;

        try {
            foreach ($ids as $id) {
                $filosofy = Filosofy::findOrFail($id);
                $filosofy->delete();
                $count++;
            }

            return response()->json(
                [
                'message' => $count.' filosofy berhasil dihapus',
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                'message' => 'Pengguna gagal dihapus',
                'error' => $e->getMessage(),
                ], 500
            );
        }
    }
}
