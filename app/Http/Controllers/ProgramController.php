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
            'departments' => Department::leftJoin('periodes', 'departments.id', '=', 'periodes.department_id')
                ->leftJoin('cabinets', 'periodes.cabinet_id', '=', 'cabinets.id')
                ->select('departments.id', 'departments.name', 'cabinets.name as cabinet_name', 'cabinets.is_active as status')
                ->where('cabinets.is_active', 1)
                ->get(),
            'users' => User::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->leftJoin('periodes', 'users.id', '=', 'periodes.user_id')
                ->leftJoin('departments', 'periodes.department_id', '=', 'departments.id')
                ->select(
                    'users.id',
                    'users.name',
                    'roles.name as role',
                    'departments.id as department_id',
                    'departments.name as department',
                    'periodes.is_active as status'
                )
                ->get(),
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

        try {
            $program = Program::create([
                'name' => $request->name,
                'description' => $request->description,
                'department_id' => $request->department,
                'user_id' => $request->user,
            ]);

            return response()->json([
                'message' => 'Program ' . $program->name . ' berhasil ditambahkan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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

        try {
            $program = Program::find($id);

            $program->update([
                'name' => $request->name,
                'description' => $request->description,
                'department_id' => $request->department,
                'user_id' => $request->user,
            ]);

            return response()->json([
                'message' => 'Program ' . $program->name . ' berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
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
                $program = Program::find($id);

                $program->delete();
                $count++;
            }

            if ($count > 0) {
                $message = 'Berhasil menghapus ' . $count . ' program';

                return response()->json([
                    'message' => $message,
                ], 200);
            }

            return response()->json([
                'message' => 'Tidak ada program yang berhasil dihapus',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
