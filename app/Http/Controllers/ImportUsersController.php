<?php

namespace App\Http\Controllers;

use App\Imports\ImportUsers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersController extends Controller
{
    public function index()
    {
        return view('pages.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new ImportUsers, $request->file('file'));
            return response()->json(['success' => 'Data berhasil diimport'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
