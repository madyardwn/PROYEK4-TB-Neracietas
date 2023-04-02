<?php

namespace App\Http\Controllers;

use App\DataTables\ProgramsDataTable;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(ProgramsDataTable $dataTable)
    {
        return $dataTable->render('pages.programs.index', [
            'departments' => Department::all(),
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'department' => 'required',
            'user' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'department' => [
                'required' => 'Departemen harus diisi',
            ],
            'user' => [
                'required' => 'Pengurus harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $program = Program::create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department,
            'user_id' => $request->user,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program ' . $program->name . ' berhasil ditambahkan');
    }

    public function edit(Program $program): Program
    {
        return $program;
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'department' => 'required',
            'user' => 'required',
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
            'department' => [
                'required' => 'Departemen harus diisi',
            ],
            'user' => [
                'required' => 'Pengurus harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $program = Program::find($id);



        $program->update([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department,
            'user_id' => $request->user,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program ' . $program->name . ' berhasil diubah');
    }

    public function destroy($id)
    {
        $program = Program::find($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program ' . $program->name . ' berhasil dihapus');
    }
}
